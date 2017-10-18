# sf2_tuto

[learn]
[shortcuts]

#bootstrapping projet symfony
symfony new <projectname> lts \
cd <projectname> \
HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1)
composer require symfony/var-dumper

#creation de bundle

##fix issue 
dans composer json remplacer "AppBundle\\": "src/AppBundle" par "": "src/" \
lancer un composer update

##generate bundle
php app/console generate:bundle \
(repondre yes pour inserer les bundles dans un vendor)

##routes
dans app/config/routing.yml on trouve référence aux controllers sous forme
sandbox_back:
    resource: "@SandboxBackBundle/Controller/"
    type:     annotation
    prefix:   /back

Ensuite, route sous forme d'annotation
    
##twig et templating
{% extends "Bundle::Default:index.html.twig" %}
{{ include("") }}
ou
{% include "Bundle:Includes:index.body.html.twig" %}
Includes dans Resources/views/Includes/ ou dans app/Resources/views/Includes (surcharge avec :)
{% include ":Includes:index.body.html.twig" %}

##Declarer un controller
utiliser annotation Route (use class)
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

#redirection

##rediriger sur la vue
return $this->render('SandboxFrontBundle:Car:car.html.twig', ['id' => $id]);

##forward et redirect
php app/console debug:router 

$url = $this->generateUrl('sandbox_front_car_car', ['id' => 4]);
return $this->redirect($url);

return $this->forward('SandboxFrontBundle:Car:Car', ['id' => 3]);


#session
php app/console debug:container <service>

##session
/* @var $session Session */
$session = $this->get('session');
$session->set('userName', 'John');

coté twig récupération var session
Username {{ app.session.get('userName') }} (pattern app.<service>.<method>.<params>)


##flashbag messages
$session->getFlashBag()->add('errors_info', 'no_error_here');

cote twig \
{{ app.session.flashbag.get('errors_info')[0] }} ou avec un for...

#creation entities
php app/console doctrine:database:create
php app/console doctrine:generate:entity
php app/console doctrine:schema:create
php app/console doctrine:schema:update --force

#DQL, repository query, QB
$repository = $this->getDoctrine()->getManager()->getRepository('SandboxBackBundle:Car');
$car = $repository->find($id);

$repository = $this->getDoctrine()->getManager()->getRepository('SandboxBackBundle:Car');
$cars = $repository->getAll();

Dans repository \

 $qb = $this->createQueryBuilder('c');

//        $query = $qb
//            ->where('c.type = :type')
//            ->setParameter('type', 'citadine')
//        ;

        $query = $qb;

        $result = $query->getQuery()->execute();

        return $result;
        
        
# Include twig with params json objects 
{% for car in cars %}
	{% include 'SandboxFrontBundle:Includes:car.info.html.twig' with {'car': car} %}
{% endfor %}

Path : 
 <td><a href="{{ path('sandbox_front_car_car', { 'id' : car.id } ) }}">Détails</a></td>
 

#Create Form, createAction, handling request, persist, redirect
 $form = $this->createFormBuilder(new Car())
            ->add('name')
            ->add('type')
            ->add('marque')
            ->add('created', 'date')
            ->add('submit', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if($request->isMethod('post') && $form->isValid()) {
//            dump($form->isValid());
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            $url = $this->generateUrl('sandbox_front_car_carlist');
            dump($url);
            return $this->redirect($url);

        }

        return $this->render('SandboxFrontBundle:Car:create.html.twig', ['form' => $form->createView()]);

Rendu du form \
{{ form(form) }}

ou custom \
->add('name', null, ['label' => 'Nom du modèle'] )

dans twig \
    {{ form_start(form) }}
    {{ form_label(form.name) }} {{ form_widget(form.name) }}
    {{ form_rest(form) }}
        

#Refactor Form create \        
 
On va déplacer le build form dans un formType pour centraliser le code et le distribuer à travers x Controller

on va passer de 
 $form = $this->createFormBuilder(new Car())
            ->add('name', null, ['label' => 'Nom du modèle'] )
            ->add('type')
            ->add('marque')
            ->add('created', 'date')
            ->add('submit', 'submit')
            ->getForm();

à cela 

$form = $this->createForm(new CarType(),new Car()); // @param new Car(); permet d'attacher le type au form afin de respecter les champs de l'entité dans le buildform


Il faut pour faire cette refactorisation déclarer un CarType dans Form/Type/CarType.php comme suit :

<?php

namespace Sandbox\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
           ->add('name', null, ['label' => 'Nom du modèle'] )
           ->add('type')
           ->add('marque')
           ->add('created', 'date')
           ->add('Champ_annexe', null, ['mapped' => false])//permet de rajouter un champ hors de l'entite
           ->add('submit', 'submit')
           ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)//permet de deduire la dataclass en cas d'imbrication de formulaire
    {
        $resolver->setDefaults( ['data_class' => 'Sandbox\BackBundle\Entity\Car'] );
    }

    public function getName()
    {
        return 'car';
    }
}


#Creation du formHandler / refactor de la handleRequest

Le formHandler va permettre de déporter le code de processing du form, il va prendre en charge les tests sur la request le type de form surlequel il doit gérer le process du form

  $carFormHandler = new CarHandler($this->createForm(new CarType(), new Car()), $request);

        if($carFormHandler->process()) {
        ...

Le formHandler va avoir la gestion de création du form, de process, et persistence
Voici la configuration des déclarations de service en yml :

parameters:
    car_form_type.class: Sandbox\FrontBundle\Form\Type\CarType
    car_handler.class: Sandbox\FrontBundle\Form\Handler\CarHandler
    symfony.form.class: Symfony\Component\Form\Form

services:
    car_form:
        factory_service: form.factory
        factory_method: createNamed
        class: %symfony.form.class%
        arguments:
            - car
            - car_form

    car_form_type:
        class: %car_form_type.class%
        tags:
            - { name: form.type, alias: car_form }

    car_handler:
        class: %car_handler.class%
        arguments: [@car_form, @request, @doctrine.orm.entity_manager]
        scope: request

Voici le prototype d'un appel du create dans un controller apres refactorisation

    /**
     * @Route("/car/create")
     */
    public function createAction(Request $request)
    {
        $carFormHandler = $this->get('car_handler');

        if($carFormHandler->process()) {
            return $this->redirect($this->generateUrl('sandbox_front_car_carlist'));
        }

        return $this->render('SandboxFrontBundle:Car:create.html.twig', ['form' => $carFormHandler->getView()]);
    }