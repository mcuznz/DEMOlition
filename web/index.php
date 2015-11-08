<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once '../src/fromCostume.php';
require_once '../src/fromTitan.php';
require_once '../src/config.php';

$app = new Silex\Application();
$app['debug'] = false;

use Silex\Provider\FormServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

$app->register(new FormServiceProvider());
$app->register(new TranslationServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => $db_config
));


$app->match('/', function(Request $request) use ($app) {

    $form = $app['form.factory']->createBuilder('form')
        ->add('attachment', 'file', array(
            'label' => 'Source File:'
        ))->getForm();

    $stats_raw = $app['db']->fetchAll("SELECT name,value FROM ".STAT_TABLE);
    $stats = array();
    foreach ($stats_raw as $s) {
        $stats[$s['name']] = $s['value'];
    }

    if ('POST' == $request->getMethod()) {
        $form->bind($request);
        
        if ($form->isValid()) {
            $file = $form['attachment']->getData();
            $ext = $file->guessExtension();
            if (!$ext) $ext = 'junk';
            $newFile = time().'-'.rand(1,9999).'.'.$ext;
            $filename = __DIR__.'/../tmp/'.$newFile;
            
            $file->move(__DIR__.'/../tmp', $newFile);
            
            // Do some stuff
            $file_contents = file_get_contents($filename);
            
            if (strpos($file_contents, 'CostumePart')) {
                // We have a .costume file
                $costumes = dataFromCostume($filename);
                $sql = "UPDATE ".STAT_TABLE." SET value=value+1 WHERE name='numCostumes'";
                $stats['numCostumes'] += 1; 
                $app['db']->executeUpdate($sql);
                unlink($filename);
                
            } elseif (strpos($file_contents, '<costumes count')) {
                // We have a Titan .xml file
                $costumes = dataFromTitan($filename);
                $sql = "UPDATE ".STAT_TABLE." SET value=value+1 WHERE name='numTitans'";
                $stats['numTitans'] += 1; 
                $app['db']->executeUpdate($sql);
                unlink($filename);
                
            } else {
                // No idea what we have
                unlink($filename);
                return $app['twig']->render('index.twig',
                    array('form' => $form->createView(),
                    'error' => "Yeah, I'm pretty sure that file you gave me was crap.",
                    'stats' => $stats));
            
            }
            
            return $app['twig']->render('step2.twig', array('costumes' => $costumes, 'stats' => $stats));
        }
    }


    return $app['twig']->render('index.twig', array('form' => $form->createView(), 'stats' => $stats));
});

$app->post('/updateDemos', function(Request $request) use ($app) {

    $sql = "UPDATE ".STAT_TABLE." SET value=value+1 WHERE name='numDemosOut'";
    $app['db']->executeUpdate($sql);
    return "1";

});

$app->match('/stats', function(Request $request) use ($app) {
    $stats_raw = $app['db']->fetchAll("SELECT name,value FROM ".STAT_TABLE);
    $stats = array();
    foreach ($stats_raw as $s) {
        $stats[$s['name']] = $s['value'];
    }
    echo '<pre>';
    print_r($stats);
    echo '</pre>';
    return "<a href='/stats'>Refresh</a>";
});

$app->run();
