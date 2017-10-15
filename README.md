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

##rediriger sur la vue
return $this->render('SandboxFrontBundle:Car:car.html.twig', ['id' => $id]);