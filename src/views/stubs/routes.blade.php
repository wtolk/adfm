$app->get('/content', PageController::class.':showAdminPageList');
$app->get('/content/add', PageController::class.':showAdminPageAdd');
$app->get('/content/{id}', PageController::class.':showAdminPageEdit');

$app->post('/page/add', '\ADFM\Controller\PageController:createPage');
$app->delete('/page/{id}', '\ADFM\Controller\PageController:softDeletePage');
$app->post('/page/{id}/edit', '\ADFM\Controller\PageController:editPage');
$app->get('/page/{id}/clone', '\ADFM\Controller\PageController:clonePage');
$app->get('/{alias}',         '\ADFM\Controller\PageController:showPage');
