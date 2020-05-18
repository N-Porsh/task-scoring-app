<?php


namespace App\Form;


use App\Entity\Client;
use App\Entity\Education;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // options in profiler
            ->add('name', null, ['label' => 'Имя'])
            ->add('surname', null,['label' => 'Фамилия'])
            ->add('phone', null, ['help' => 'Не вводить код страны', 'label' => 'Телефон', 'attr'=> ['placeholder' => 'Прим: 92557556983']])
            ->add('email', EmailType::class, ['label' => 'Е-майл'])
            ->add('education', EntityType::class, [
                'class' => Education::class,
                'label' => 'Образование',
                'placeholder' => "Выберите образование",
                'invalid_message' => 'Неправильное значение!'
//                'choice_label' => function(Education $client) {
//                    return sprintf('(%d) %s', $client->getId(), $client->getValue());
//                }
            ])
            //->add('createdAt', null, ['widget' => 'single_text'])
            ->add('processData', null, ['label' => 'Я даю согласие на обработку моих личных данных']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class
        ]);
    }

}