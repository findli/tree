<?php
namespace User\Controller;

use Std\My\DefaultController;
use User\Document\Image;
use Zend\View\Model\ViewModel;

use Zend\Http\Headers;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

use User\Document\User;

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

use Zend\Stdlib\ArrayUtils;

class UploadManagerController extends DefaultController
{

	public function getFileUploadLocation()
	{
		// Fetch Configuration from Module Config
		$config = $this->getServiceLocator()
		               ->get( 'config' );

		return $config[ 'module_config' ][ 'upload_location' ];
	}

	public function processUploadAction()
	{

		$request = $this->getRequest();
		if ( $request->isPost() ) {

			$uploadFile = $this->params()
			                   ->fromFiles( 'file' );
//			$file_src   = '/tmp/'.$uploadFile['name'];
			// Fetch Configuration from Module Config
			$uploadPath = $this->getFileUploadLocation();

//			copy('C:\php\upload\\' . $uploadFile['name'], $file_src);
//			d($uploadFile);
//			dd($file_src);
			// Save Uploaded file
			$adapter = new \Zend\File\Transfer\Adapter\Http();
			$adapter->setDestination( $uploadPath );

			$validatorIsImage = new \Zend\Validator\File\IsImage();
//			$val[ ]           = [
//				'isImage'  => $validatorIsImage->isValid( $uploadFile[ 'tmp_name' ] ),
//				'messages' => $validatorIsImage->getMessages(),
//			];
//			$val[ ]           = [
//				'size'     => ( $uploadFile[ 'size' ] > 1000 && $uploadFile < 5000000 ) ? TRUE : FALSE,
//				'messages' => [ 'File size must be bigger than 1 kBite and less than 5 MB' ]
//			];
			$size  = new \Zend\Validator\File\Size( array( "min" => 1000, "max" => 10000000 ) );
			$count = new \Zend\Validator\File\Count( array( "min" => 0, "max" => 1 ) );
//			$val[ ]           = [
//				'count'    => $count->isValid( $uploadFile[ 'tmp_name' ] ),
//				'messages' => $count->getMessages()
//			];
			$extension = new \Zend\Validator\File\Extension( array( "extension" => array( "jpg", "png", "jpeg", "gif" ) ) );
//			$val[ ]           = [
//				'extension' => $extension->isValid( $uploadFile[ 'tmp_name' ] ),
//				'messages'  => $extension->getMessages(),
//			];
			$adapter->addValidators( [
				                         $validatorIsImage,
				                         $count,
				                         $extension,
				                         $size,
			                         ] );
			$extension          = explode( '/', $uploadFile[ 'type' ] )[ 1 ];
			$str                = $uploadPath . "/" . $this->identity()
			                                               ->getId() . "." . $extension;
			$filterRenameUpload = new \Zend\Filter\File\Rename( [ 'target' => $str, 'randomize' => TRUE ] );
			$adapter->addFilter( $filterRenameUpload );
			if ( $adapter->receive( $uploadFile[ 'name' ] ) ) {
				$fileOriginal  = $adapter->getFileInfo()[ 'file' ][ 'name' ];
				$fileThumbnail = $this->generateThumbnail( $fileOriginal );

				$dm = $this->getServiceLocator()
				           ->get( 'doctrine.documentmanager.odm_default' );
//				$avatarOriginal = new Image;
//				$avatarOriginal->setFile( $uploadPath . '\\' .$fileOriginal );
//				$dm->persist( $avatarOriginal );
//				$avatar         = new Image;
//				$avatar->setFile( $uploadPath . '\\' .$fileThumbnail );
//				$dm->persist( $avatar );
//				$dm->flush();

//				$setFile = new Image;
//				$file = $uploadPath . '\\' . $fileThumbnail;
//				d($file);
//				$setFile->setFile( $file );
//				d(get_class($setFile));
				$user = $res = $dm->createQueryBuilder( 'User\Document\User' )
				                  ->field( '_id' )
				                  ->equals( new \MongoId( $this->identity()
				                                               ->getId() ) )
				                  ->getQuery()
				                  ->getSingleResult();

				$avatar = $user->getAvatar();
				$str1   = $uploadPath . '\\' . $fileThumbnail;
				$avatar->setFile( $str1 );
				$avatarOriginal = $user->getAvatarOriginal();
				$str2           = $uploadPath . '\\' . $fileOriginal;
				$avatarOriginal->setFile( $str2 );
				$dm->persist( $avatarOriginal );
				$dm->persist( $avatar );
				$dm->flush();
//				foreach ( $res as $v ) {
//					$user = $v;
//				}
//				d(get_class_methods($user->getAvatarOriginal()));
//				d($user->getAvatarOriginal()->hasUnpersistedBytes());
//				d($user->getAvatarOriginal()->getMongoGridFSFile());
//				d($user->getAvatarOriginal()->getFilename());
//				d($user->getAvatarOriginal()->getSize());
//				dd(get_class($user));
//				$dm->persist($user);
//				$dm->flush();
				/*
				dd($file);
				$exchange_data = [ ];
				$tmp1          = explode( '/', $file );
				$fileName      = end( $tmp1 );
				unset( $tmp1 );
				$exchange_data[ 'avatar_src' ] = $fileName;
				$exchange_data[ 'id' ]         = $auth[ 'user' ][ 'id' ];

				$user->exchangeArray( $exchange_data );

				$userTable = $this->getServiceLocator()
				                  ->get( 'UserTable' );
				$userTable->saveAvatar( $user );

				$authUser = $this->getAuthService()->getStorage()->read();
				$authUser['user']['avatar_src'] = $fileName;*/
				$avatarDb = base64_encode( $avatar->getFile()
				                                  ->getBytes() );
				/*
				 * update user in session
				 */
				$storage      = $this->getServiceLocator()
				                     ->get( 'authService' )
				                     ->getStorage();
				$user         = $storage->read();
				$user->setAvatar( $avatarDb );
				$storage->write( $user );
				/*
				 * -----------------
				 */
				/*
				 * update user in db
				 */
//				$adapter = $this->getServiceLocator()
//				                ->get( 'authService' )
//				                ->getAdapter();
//				$params  = [ 'avatar' => $avatarDb ];
//				$adapter->updateUser( $params, $user->getId() );
				/*
				 * ------------------
				 */

				echo json_encode( [
					                  'result'   => TRUE,
					                  'messages' => '',
					                  'avatar'   => $avatarDb,
				                  ] );
				die;
			} else {
				if ( $adapter->getMessages() ) {
					$messages1 = $adapter->getMessages();
					$messages  = "File upload error:\n";
					for ( $i = 0; $i <= count( $messages1 ) - 1; $i++ ) {
						$messages .= $i . ') ' . current( $messages1 ) . " \n";
						next( $messages1 );
					}
				}
				echo json_encode( [
					                  'result'   => ( $adapter->getMessages() ) ? FALSE : TRUE,
					                  'messages' => $messages,
				                  ] );
//				d($messages);
//				d( 'error understand' );
//				dd( $adapter->getMessages() );
				die;
			}
		} else {
			dd( "it's not post ))))))))))))" );
		}


		$vm = new ViewModel();
		$vm->setTemplate( 'user/uploadmanager/processupload' );
		$vm->setTerminal( 1 );

		return $vm;
	}

	function processupload2Action()
	{
		$request = $this->getRequest();
		if ( $request->isPost() ) {

			$data = array_merge_recursive(
				$this->getRequest()
				     ->getPost()
				     ->toArray(),
				$this->getRequest()
				     ->getFiles()
				     ->toArray()
			);

//dd($data);
//			$file_src   = 'C:\php\upload\\'.$data['thumb']['name'];
//			move_uploaded_file($data['thumb']['tmp_name'], $file_src);


			$dm         = $this->getServiceLocator()
			                   ->get( 'doctrine.documentmanager.odm_default' );
			$course     = new User();
			$uploadPath = $this->getFileUploadLocation();

//			$course->setAvatar($uploadPath . '\learning-pyramid.jpg');
//			$dm->persist($course);
//			$dm->flush();

			$res = $dm->createQueryBuilder( 'User\Document\User' )
			          ->field( 'id' )
			          ->equals( $this->identity()
			                         ->getId() )
			          ->getQuery()
			          ->execute();
			foreach ( $res as $v ) {
				$user = $v;
			}
			$avatarDb = base64_encode( $user->getAvatar()
			                                ->getBytes() );
			dd( $avatarDb );
		}
	}

	function safeAvatarAction()
	{
		$image = new User();
		$image->setName( 'avatar' );
		$uploadPath = $this->getFileUploadLocation();
		$image->setAvatar( $uploadPath . '\learning-pyramid.jpg' );
		$dm = $this->getServiceLocator()
		           ->get( 'doctrine.documentmanager.odm_default' );

		$dm->persist( $image );
		$dm->flush();
		die;
	}

	function getAvatarAction()
	{
		$dm    = $this->getServiceLocator()
		              ->get( 'doctrine.documentmanager.odm_default' );
		$image = $dm->createQueryBuilder( 'User\Document\User' )
		            ->field( 'name' )
		            ->equals( 'avatar' )
		            ->getQuery()
		            ->getSingleResult();

		header( 'Content-type: image/jpeg;' );
		header( 'Content-transfer-encoding: base64' );
		echo $image->getAvatar()
		           ->getBytes();
//		echo '<img src="data:image/jpg;base64,'.$image->getAvatar()->getBytes().'">';
	}

	function testmoveAction()
	{
		$uploadPath = $this->getFileUploadLocation();
		d( is_writable( $uploadPath ) );
		d( filesize( $uploadPath . '\learning-pyramid.jpg' ) );
		move_uploaded_file( $uploadPath . '\learning-pyramid.jpg', $uploadPath . '\pyramid2.jpg' );
		die;
	}

	public function generateThumbnail( $imageFileName )
	{
		$path                = $this->getFileUploadLocation();
		$sourceImageFileName = $path . '/' . $imageFileName;
		$thumbnailFileName   = 'tn_' . $imageFileName;

		$imageThumb = $this->getServiceLocator()
		                   ->get( 'WebinoImageThumb' );
		$thumb      = $imageThumb->create( $sourceImageFileName, $options = array() );
		$thumb->resize( 175, 175 );
		$thumb->save( $path . '/' . $thumbnailFileName );

		return $thumbnailFileName;
	}

	function createAction()
	{
		$dm    = $this->getServiceLocator()
		              ->get( 'doctrine.documentmanager.odm_default' );
		$image = new Image();
		$image->setName( 'Test image' );
		$uploadPath = $this->getFileUploadLocation();
		$image->setFile( $uploadPath . '\learning-pyramid.jpg' );

		$dm->persist( $image );
		$dm->flush();
		die;
	}

	function getAction()
	{
		$dm    = $this->getServiceLocator()
		              ->get( 'doctrine.documentmanager.odm_default' );
		$image = $dm->createQueryBuilder( 'User\Document\Image' )
		            ->field( 'name' )
		            ->equals( 'Test image' )
		            ->getQuery()
		            ->getSingleResult();

//		header('Content-type: image/jpg;');
		echo $image->getFile()
		           ->getBytes();
//		echo '<img src="data:image/png;base64,'.$image->getFile()->getBytes().'" >';
		die;
	}

	function testAction()
	{

		$dm = $this->getServiceLocator()
		           ->get( 'doctrine.documentmanager.odm_default' );
		$id = $this->identity()
		           ->getId();
		d( $id );
		$user = $dm->createQueryBuilder( 'User\Document\User' )
		           ->field( '_id' )
		           ->equals( new \MongoId( $id ) )
		           ->getQuery()
		           ->getSingleResult();

		$image = new Image();
		$image->setName( 'Test image' );
		$uploadPath = $this->getFileUploadLocation();
		$file       = $uploadPath . '\learning-pyramid.jpg';
		$image->setFile( $file );

		$avatar = $user->getAvatar();
		$avatar->setFile( $file );
		$dm->persist( $avatar );
		$dm->flush();


		$user = $dm->createQueryBuilder( 'User\Document\User' )
		           ->field( '_id' )
		           ->equals( new \MongoId( $id ) )
		           ->getQuery()
		           ->getSingleResult();

		d( ( $user->getAvatar()
		          ->getFile()
		          ->getBytes() ) );
		die;
	}
}