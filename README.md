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
dans app/config/routing.yml on trouve référence aux controllers sous fo
##twig et templating

