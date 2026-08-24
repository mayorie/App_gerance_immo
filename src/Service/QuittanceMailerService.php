<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Twig\Environment;
use App\Entity\Locataires;
use App\Repository\PaiementsMensuelsRepository;
use Psr\Log\LoggerInterface;

class QuittanceMailerService
{
    private MailerInterface $mailer;
    private Environment $twig;
    private LoggerInterface $logger;
    private PaiementsMensuelsRepository $paiementsRepo;
    private string $fromEmail;

    public function __construct(
        MailerInterface $mailer,
        Environment $twig,
        LoggerInterface $logger,
        PaiementsMensuelsRepository $paiementsRepo,
        string $fromEmail = '%env(MAILER_FROM_EMAIL)%'
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->logger = $logger;
        $this->paiementsRepo = $paiementsRepo;
        $this->fromEmail = $fromEmail;
    }

    /**
     * Envoie une quittance par email à un locataire
     *
     * @param string $pdfContent Contenu binaire du PDF
     * @param Locataires $locataire Le locataire destinataire
     * @param int $mois Le mois de la quittance
     * @param int $annee L'année de la quittance
     * @param float|null $restantDuTropPercu Solde calculé (optionnel)
     * @return bool True si l'envoi a réussi, false sinon
     */
    public function sendQuittance(
        string $pdfContent,
        Locataires $locataire,
        int $mois,
        int $annee,
        ?float $restantDuTropPercu = null
    ): bool {
        try {
            $email = $locataire->getMail();

            if (empty($email)) {
                $this->logger->warning('Locataire sans email', [
                    'locataire_id' => $locataire->getId(),
                    'nom' => $locataire->getNom(),
                    'prenom' => $locataire->getPrenom()
                ]);
                return false;
            }

            $this->logger->info('Tentative d\'envoi de quittance', [
                'locataire_id' => $locataire->getId(),
                'email' => $email,
                'mois' => $mois,
                'annee' => $annee
            ]);

            $nomMois = $this->getNomMois($mois);
            
            $filename = $locataire->getNom() . '_' . $locataire->getPrenom() . '_' . $annee . '_' . sprintf("%02d", $mois) . '.pdf';

            $subject = sprintf('Quittance de loyer - %s %d', $nomMois, $annee);
            if ($restantDuTropPercu !== null && $restantDuTropPercu != 0) {
                if ($restantDuTropPercu > 0) {
                    $montantStr = ($restantDuTropPercu == (int)$restantDuTropPercu)
                        ? (string)(int)$restantDuTropPercu
                        : number_format($restantDuTropPercu, 2, ',', ' ');
                    $subject .= sprintf(' - RESTANT DU DE %s €', $montantStr);
                } else {
                    $absMontant = abs($restantDuTropPercu);
                    $montantStr = ($absMontant == (int)$absMontant)
                        ? (string)(int)$absMontant
                        : number_format($absMontant, 2, ',', ' ');
                    $subject .= sprintf(' - TROP PERCU DE %s €', $montantStr);
                }
            }

            $email = (new Email())
                ->from($this->fromEmail)
                ->to($email)
                ->subject($subject)
                ->html($this->twig->render('email/quittance.html.twig', [
                    'locataire' => $locataire,
                    'mois' => $mois,
                    'annee' => $annee,
                    'nomMois' => $nomMois,
                    'restantDuTropPercu' => $restantDuTropPercu
                ]))
                ->attach($pdfContent, $filename, 'application/pdf');

            $this->mailer->send($email);

            $this->logger->info('Quittance envoyée avec succès', [
                'locataire_id' => $locataire->getId(),
                'email' => $email,
                'mois' => $mois,
                'annee' => $annee
            ]);

            return true;

        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'envoi de la quittance', [
                'locataire_id' => $locataire->getId(),
                'email' => $email,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return false;
        }
    }

    /**
     * Envoie une relance impayé par email à un locataire
     *
     * @param string $pdfContent Contenu binaire du PDF
     * @param Locataires $locataire Le locataire destinataire
     * @param int $mois Le mois de la quittance
     * @param int $annee L'année de la quittance
     * @param float $montantDu Le montant dû
     * @return bool True si l'envoi a réussi, false sinon
     */
    public function sendRelanceImpaye(
        string $pdfContent,
        Locataires $locataire,
        int $mois,
        int $annee,
        float $montantDu
    ): bool {
        try {
            $email = $locataire->getMail();

            if (empty($email)) {
                $this->logger->warning('Locataire sans email pour relance', [
                    'locataire_id' => $locataire->getId(),
                    'nom' => $locataire->getNom(),
                    'prenom' => $locataire->getPrenom()
                ]);
                return false;
            }

            $this->logger->info('Tentative d\'envoi de relance impayé', [
                'locataire_id' => $locataire->getId(),
                'email' => $email,
                'mois' => $mois,
                'annee' => $annee,
                'montant_du' => $montantDu
            ]);

            $nomMois = $this->getNomMois($mois);
            
            $filename = $locataire->getNom() . '_' . $locataire->getPrenom() . '_' . $annee . '_' . sprintf("%02d", $mois) . '.pdf';

            $email = (new Email())
                ->from($this->fromEmail)
                ->to($email)
                ->subject('RELANCE IMPAYE DE LOYER')
                ->html($this->twig->render('email/relance_impaye.html.twig', [
                    'locataire' => $locataire,
                    'mois' => $mois,
                    'annee' => $annee,
                    'nomMois' => $nomMois,
                    'montantDu' => $montantDu
                ]))
                ->attach($pdfContent, $filename, 'application/pdf');

            $this->mailer->send($email);

            $this->logger->info('Relance impayé envoyée avec succès', [
                'locataire_id' => $locataire->getId(),
                'email' => $email,
                'mois' => $mois,
                'annee' => $annee
            ]);

            return true;

        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'envoi de la relance impayé', [
                'locataire_id' => $locataire->getId(),
                'email' => $email,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return false;
        }
    }

    /**
     * Retourne le nom du mois en français
     */
    private function getNomMois(int $mois): string
    {
        $moisNoms = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        ];

        return $moisNoms[$mois] ?? '';
    }
}
