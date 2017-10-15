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