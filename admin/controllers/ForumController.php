<?php
namespace cmsgears\modules\forum\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;
use cmsgears\modules\forum\common\config\ForumGlobal;

use cmsgears\modules\core\common\models\entities\CmgFile;
use cmsgears\modules\forum\common\models\entities\ForumPermission;
use cmsgears\modules\forum\common\models\entities\Forum;
use cmsgears\modules\forum\common\models\entities\ForumTopic;
use cmsgears\modules\forum\common\models\entities\ForumReply;

use cmsgears\modules\forum\admin\models\forms\ForumCategoryBinderForm;

use cmsgears\modules\core\admin\services\CategoryService;
use cmsgears\modules\forum\admin\services\ForumService;
use cmsgears\modules\forum\admin\services\ForumTopicService;
use cmsgears\modules\forum\admin\services\ForumReplyService;

use cmsgears\modules\core\admin\controllers\BaseController;

use cmsgears\modules\core\common\utilities\MessageUtil;

class ForumController extends BaseController {

	const URL_ALL 	= 'all';

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'permissions' => [
	                'index'  => ForumPermission::PERM_FORUM,
	                'all'   => ForumPermission::PERM_FORUM,
	                'create' => ForumPermission::PERM_FORUM,
	                'update' => ForumPermission::PERM_FORUM,
	                'delete' => ForumPermission::PERM_FORUM,
	                'view' => ForumPermission::PERM_FORUM,
	                'createTopic' => ForumPermission::PERM_FORUM,
	                'updateTopic' => ForumPermission::PERM_FORUM,
	                'deleteTopic' => ForumPermission::PERM_FORUM,
	                'viewTopic' => ForumPermission::PERM_FORUM,
	                'createPost' => ForumPermission::PERM_FORUM,
	                'updatePost' => ForumPermission::PERM_FORUM,
	                'deletePost' => ForumPermission::PERM_FORUM
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index'  => ['get'],
	                'all'   => ['get'],
	                'create' => ['get', 'post'],
	                'update' => ['get', 'post'],
	                'delete' => ['get', 'post'],
	                'view' => ['get'],
	                'createTopic' => ['get', 'post'],
	                'updateTopic' => ['get', 'post'],
	                'deleteTopic' => ['get', 'post'],
	                'viewTopic' => ['get'],
	                'createPost' => ['get', 'post'],
	                'updatePost' => ['get', 'post'],
	                'deletePost' => ['get', 'post']
                ]
            ]
        ];
    }

	public function actionIndex() {

		$this->redirect( self::URL_ALL );
	}

	public function actionAll() {
		
	    $pagination = ForumService::getPagination();

	    return $this->render('all', [
	         'page' => $pagination['page'],
	         'pages' => $pagination['pages'],
	         'total' => $pagination['total']
	    ]);
	}

	public function actionMatrix() {

		$pagination 	= ForumService::getPagination();
		$categories		= CategoryService::getIdNameMapByType( ForumGlobal::CATEGORY_TYPE_FORUM );

	    return $this->render('matrix', [
	         'page' => $pagination['page'],
	         'pages' => $pagination['pages'],
	         'total' => $pagination['total'],
	         'allCategories' => $categories
	    ]);
	}

	public function actionCreate() {

		$model	= new Forum();
		$banner = new CmgFile();

		$model->setScenario( "create" );

		if( $model->load( Yii::$app->request->post( "Forum" ), "" )  && $model->validate() ) {

			$banner 		= new CmgFile();

			$banner->load( Yii::$app->request->post( "File" ), "" );

			if( ForumService::create( $model, $banner ) ) {

				$binder = new ForumCategoryBinderForm();

				$binder->forumId	= $model->getId();
				$binder->load( Yii::$app->request->post( "Binder" ), "" );

				ForumService::bindCategories( $binder );

				return $this->redirect( [ self::URL_ALL ] );
			}
		}

		$categories		= CategoryService::getIdNameMapByType( ForumGlobal::CATEGORY_TYPE_FORUM );

    	return $this->render('create', [
    		'model' => $model,
    		'banner' => $banner,
    		'categories' => $categories
    	]);
	}

	public function actionUpdate( $id ) {
		
		$model	= ForumService::findById( $id );
		$banner = new CmgFile();

		if( isset( $model ) ) {

			$model->setScenario( "update" );

			if( $model->load( Yii::$app->request->post( "Forum" ), "" )  && $model->validate() ) {

				$banner 		= new CmgFile();		

				$banner->load( Yii::$app->request->post( "File" ), "" );

				if( ForumService::update( $model, $banner ) ) {

					$binder = new ForumCategoryBinderForm();
	
					$binder->forumId	= $model->getId();
					$binder->load( Yii::$app->request->post( "Binder" ), "" );
	
					ForumService::bindCategories( $binder );

					$this->refresh();
				}
			}

			$categories		= CategoryService::getIdNameMapByType( ForumGlobal::CATEGORY_TYPE_FORUM );
			$banner			= $model->banner;

	    	return $this->render( 'update', [
	    		'model' => $model,
	    		'banner' => $banner,
	    		'categories' => $categories
	    	]);
		}

		throw new NotFoundHttpException( MessageUtil::getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		$model	= ForumService::findById( $id );

		if( isset( $model ) ) {

			if( isset( $_POST ) && count( $_POST ) > 0 ) {

				if( ForumService::delete( $model ) ) {

					return $this->redirect( [ self::URL_ALL ] );
				}
			}

			$categories		= CategoryService::getIdNameMapByType( ForumGlobal::CATEGORY_TYPE_FORUM );
			$banner			= $model->banner;

	    	return $this->render( 'delete', [
	    		'model' => $model,
	    		'banner' => $banner,
	    		'categories' => $categories
	    	]);
		}

		throw new NotFoundHttpException( MessageUtil::getMessage( CoreGlobal::ERROR_NOT_FOUND ) );	
	}

	// Categories -------------------

	public function actionCategories() {

		$pagination = CategoryService::getPaginationByType( ForumGlobal::CATEGORY_TYPE_FORUM );

	    return $this->render('categories', [
	         'page' => $pagination['page'],
	         'pages' => $pagination['pages'],
	         'total' => $pagination['total'],
	         'type' => ForumGlobal::CATEGORY_TYPE_FORUM
	    ]);
	}

	// Topic ===============================================================

	public function actionView( $fid ) {

		$forum			= Forum::findById( $fid );

		if( isset( $forum ) ) {

			$pagination	= ForumTopicService::getPaginationByForumId( $fid );
			
			return $this->render( 'topic/view', [
				'forum' => $forum,
				'page' => $pagination['page'],
				'pages' => $pagination['pages'],
				'total' => $pagination['total']
	    	]);
		}

		throw new NotFoundHttpException( MessageUtil::getMessage( CoreGlobal::ERROR_NOT_FOUND ) );	
	}

	public function actionCreateTopic( $fid ) {

		$model		= new ForumTopic();

		$model->setScenario( "create" );

		if( $model->load( Yii::$app->request->post( "ForumTopic" ), "" )  && $model->validate() ) {

			if( ForumTopicService::create( $model, $fid ) ) {
				
				return $this->redirect( ["/cmgforum/forum/view?id=$fid"] );
			}
		}

    	return $this->render('topic/create', [
    		'model' => $model,
    		'forumId' => $fid
    	]);
	}

	public function actionUpdateTopic( $fid, $id ) {
		
		$model		= ForumTopicService::findById( $id );
		$forumId 	= $model->getForumId();

		if( isset( $model ) && $fid == $forumId ) {

			$model->setScenario( "update" );
	
			if( $model->load( Yii::$app->request->post( "ForumTopic" ), "" )  && $model->validate() ) {
	
				if( ForumTopicService::update( $model ) ) {
	
					$this->refresh();
				}
			}

	    	return $this->render('topic/update', [
	    		'model' => $model
	    	]);			
		}

		throw new NotFoundHttpException( MessageUtil::getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDeleteTopic( $fid, $id ) {

		$model		= ForumTopicService::findById( $id );
		$forumId 	= $model->getForumId();

		if( isset( $model ) && $fid == $forumId ) {

			if( isset( $_POST ) && count( $_POST ) > 0 ) {

				if( ForumTopicService::delete( $model ) ) {

					return $this->redirect( [ "/cmgforum/forum/view?id=$fid" ] );
				}
			}

	    	return $this->render('topic/delete', [
	    		'model' => $model,
	    	]);
		}

		throw new NotFoundHttpException( MessageUtil::getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
	
	public function actionViewTopic( $tid ) {

		$topic			= ForumTopic::findById( $tid );

		if( isset( $topic ) ) {

			$pagination   	= ForumReplyService::getPaginationByTopicId( $tid );

			return $this->render('topic/reply/view', [
	    		'topic' => $topic,
	    		'page' => $pagination['page'],
	    		'pages' => $pagination['pages'],
	    		'total' => $pagination['total']
	    	]);
		}

		throw new NotFoundHttpException( MessageUtil::getMessage( CoreGlobal::ERROR_NOT_FOUND ) );	
	}

	// Post ===============================================================

	public function actionCreateReply( $tid ) {

		$model		= new ForumReply();

		$model->setScenario( "create" );

		if( $model->load( Yii::$app->request->post( "ForumReply" ), "" )  && $model->validate() ) {

			if( ForumReplyService::create( $model, $tid ) ) {
				
				return $this->redirect(["/cmgforum/forum/view-topic?tid=$tid"] );
			}
		}

    	return $this->render('topic/reply/create', [
    		'model' => $model,
    		'topicId' => $tid
    	]);
	}
	
	public function actionUpdateReply( $tid, $id ) {
		
		$model		= ForumReplyService::findById( $id );
		$topicId 	= $model->getTopicId();

		if( isset( $model ) && $tid == $topicId ) {

			$model->setScenario( "update" );
	
			if( $model->load( Yii::$app->request->post( "ForumReply" ), "" )  && $model->validate() ) {
	
				if( ForumReplyService::update( $model ) ) {
	
					$this->refresh();
				}
			}

	    	return $this->render('topic/reply/update', [
	    		'model' => $model
	    	]);			
		}

		throw new NotFoundHttpException( MessageUtil::getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
	
	public function actionDeleteReply( $tid, $id ) {

		$model		= ForumReplyService::findById( $id );
		$topicId 	= $model->getTopicId();

		if( isset( $model ) && $tid == $topicId ) {

			if( isset( $_POST ) && count( $_POST ) > 0 ) {
	
				if( ForumReplyService::delete( $model ) ) {
		
					return $this->redirect( [ "/cmgforum/forum/view-topic?tid=$topicId" ] );
				}
			}

			$forums	= ForumService::getIdNameMap();
			
	    	return $this->render('topic/reply/delete', [
	    		'model' => $model,
	    	]);
		}

		throw new NotFoundHttpException( MessageUtil::getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>