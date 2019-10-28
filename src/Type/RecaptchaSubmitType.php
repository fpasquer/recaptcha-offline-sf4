<?php

namespace fpasquer\SimpleRecaptchaBundle\src\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecaptchaSubmitType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Submit',
            'mapped' => false
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['label'] = false;
        $view->vars['button'] = $options['label'];
    }

    public function getBlockPrefix()
    {
        return 'simple_recaptcha';
    }

    public function getParent()
    {
        return TextType::class;
    }
}