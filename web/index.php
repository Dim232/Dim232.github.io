<?php

require('../vendor/autoload.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Our web handlers
$app->get('/', function() use($app) {
  return "<h1>Бот Anime-RP</h1>
  На данный момент в разработке";

  $data = json_decode(file_get_contents('php://input'));

  if( !$data )
    return 'null';
  
  if( $data->secret !== getenv('VK_SECRET_TOKEN') && $data->type !== 'confirmation' )
    return 'null';

  switch( $data->type )
  {
    case 'confirmation':
      return getenv('VK_CONFIRMATION_CODE');
      break;
  }
});



//########################################################################################
//########################################################################################
//########################################################################################




// Для получения запросов от ВК
$app->post('/bot', function() use($app) {
  $data = json_decode(file_get_contents('php://input'));
  
  if( !$data )
    return 'error';
  
  if( $data->secret !== getenv('VK_SECRET_TOKEN') && $data->type !== 'confirmation' )
    return 'error';

  switch( $data->type )
  {
    case 'confirmation':
      return getenv('VK_CONFIRMATION_CODE');
      break;

// Сообщение от пользователя
    case 'message_new':
      $request_params = array(
        'message' => "Hello!",
        'peer_id' => $user_id,
        'access_token' => $token,
        'v' => '5.103',
        'random_id' => '0'
      );
      
      $get_params = http_build_query($request_params);
      file_get_contents('https://api.vk.com/method/messages.send?'. $get_params);
      return 'ok';
      break;
  }
  
  return 'error';
});

$app->run();
