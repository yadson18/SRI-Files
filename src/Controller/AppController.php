<?php 
	namespace App\Controller;

	use Simple\Controller\Controller;
	use Simple\Http\Request;
	use Simple\View\View;


	use Simple\Configurator\Configurator;

	abstract class AppController extends Controller
	{
		/**
		 * initialize Method.
		 *
		 * @param 
		 *		Simple\Http\Request $request - server request.
		 *		Simple\View\View $view - current view controller.
		 * @return 
		 *		null
		 */
		public function initialize(Request $request, View $view)
		{
			parent::initialize($request, $view);

			$this->loadComponent('Ajax');

			$this->loadComponent('Flash');

			$this->loadComponent('Paginator');

			$this->loadComponent('Auth');
		}	

		/**
		 * alowedMethods Method.
		 *
		 * @param 
		 *		string $method - method name to check access authorization.
		 *		array $methods - methods with authorized access.
		 * @return 
		 *		boolean
		 */
		public function allow(array $methods)
        {
        	$view = $this->request->getHeader()->view;

        	if (in_array($view, $methods)) {
                return true;
            }
            else if (is_callable([$this->Auth, 'getUser']) &&
            	!empty(call_user_func([$this->Auth, 'getUser'])) &&
            	is_callable([$this->Auth, 'checkAuthorization']) &&
            	call_user_func_array([$this->Auth, 'checkAuthorization'], [$view])
        	) {
            	return true;
            }
            return false;
        }
		
		public abstract function beforeFilter();
	}