<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use App\Enums\UserProfil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Formulaire d'inscription pour créer un nouveau compte utilisateur
 *
 * Ce formulaire collecte les informations nécessaires pour créer un compte
 * sur la plateforme HandStar Connect, incluant le profil utilisateur
 * qui détermine les fonctionnalités accessibles.
 */
class RegistrationFormType extends AbstractType
{
    /**
     * Construit le formulaire d'inscription avec tous les champs nécessaires
     *
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Les options de configuration
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ prénom : requis pour l'identification personnelle
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Votre prénom',
                    'autocomplete' => 'given-name', // Améliore l'accessibilité et l'UX
                ],
                'constraints' => [
                    new NotBlank(message: 'Veuillez saisir votre prénom.'),
                ],
            ])
            // Champ nom : requis pour l'identification personnelle
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Votre nom',
                    'autocomplete' => 'family-name', // Améliore l'accessibilité et l'UX
                ],
                'constraints' => [
                    new NotBlank(message: 'Veuillez saisir votre nom.'),
                ],
            ])
            // Champ profil : détermine le type de compte et les permissions
            ->add('profil', ChoiceType::class, [
                'label' => 'Profil utilisateur',
                'choices' => UserProfil::getChoicesByPriority(), // Utilise l'énumération triée par priorité
                'placeholder' => 'Sélectionnez votre profil', // Texte par défaut pour la sélection
                'attr' => [
                    'class' => 'form-select', // Classe Bootstrap pour les sélecteurs
                ],
                'constraints' => [
                    new NotBlank(message: 'Veuillez sélectionner votre profil.'),
                ],
            ])
            // Champ email : identifiant unique de connexion
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => [
                    'placeholder' => 'exemple@email.com',
                    'autocomplete' => 'email', // Améliore l'accessibilité et l'UX
                ],
                'constraints' => [
                    new NotBlank(message: 'Veuillez saisir une adresse email.'),
                ],
            ])
            // Case à cocher pour accepter les conditions d'utilisation
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false, // Non mappé à l'entité, utilisé uniquement pour la validation
                'label' => "J'accepte les conditions d'utilisation",
                'constraints' => [
                    new IsTrue(message: 'Vous devez accepter les conditions d\'utilisation.'),
                ],
            ])
            // Champ mot de passe : non mappé directement, encodé dans le contrôleur
            ->add('plainPassword', PasswordType::class, [
                // Au lieu d'être défini directement sur l'objet,
                // ce champ est lu et encodé dans le contrôleur
                'mapped' => false,
                'label' => 'Mot de passe',
                'attr' => [
                    'autocomplete' => 'new-password', // Indique aux navigateurs qu'il s'agit d'un nouveau mot de passe
                    'placeholder' => 'Votre mot de passe',
                ],
                'constraints' => [
                    new NotBlank(message: 'Veuillez saisir un mot de passe.'),
                    new Length(
                        min: 6,
                        max: 4096, // Limite maximale imposée par Symfony pour des raisons de sécurité
                        minMessage: 'Votre mot de passe doit contenir au moins {{ limit }} caractères.'
                    ),
                ],
            ])
        ;
    }

    /**
     * Configure les options par défaut du formulaire
     *
     * @param OptionsResolver $resolver Le résolveur d'options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class, // L'entité cible pour la persistance des données
        ]);
    }
}
