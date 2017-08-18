<?php

class Template_Smarty {

	public $logService;

	public function m_appurl($params, $smarty) {
		$ep = '';
		if (!is_array($params)) {
			$ep = $params;
			$ep = implode('/', $params);
		} else if (isset($params['ep'])) {
			$ep = implode('/', $params);
//			$ep = $params['ep'];
		}

		return m_appurl($ep);
	}


	public  function smarty_appurl($params) {
			return m_appurl($params);
	}
	/*
	*/

	public function template($request, $response, $template_section) {
		if ($response->statusCode == 500) {
			return;
		}
		$fileChoices = [];
		$filesep     = '/';
		$viewFile    = _get('template.main.file', '');
		//leading slash indicates template file
		if (substr($viewFile, 0, 1) ==  '/') {
			$viewFileList[] = $this->baseDir._get('template_name').$viewFile;
		} else {

			$viewFileList   = [];
			$mainFile       = _get('template.main.file', $request->modName.'_'.$request->actName);
			$viewFileList[] = 'src/'.$request->appName.'/views/'.$mainFile;
			$mainFile       = _get('template.main.file', $request->appName.'/'.$request->modName);
			$viewFileList[] = _get('template_basedir') . _get('template_name') .'/'.str_replace('.', '/', $mainFile);
		}

		$smarty = new Smarty();
		$smarty->debugging       = FALSE;
		$smarty->escape_html     = TRUE;
		$smarty->error_reporting = E_ALL & ~E_NOTICE;

		$smarty->setCompileDir('var/cache/smarty/');
		$smarty->assign('baseurl',   m_url());
		$smarty->assign('turl',      m_turl());
		$smarty->assign('pageTitle', _get('page_title'));
		$smarty->assign($response->sectionList);

		$smarty->registerPlugin("modifier", "appurl", array($this, 'smarty_appurl'));
		$smarty->registerPlugin("function", "m_appurl", array($this, 'm_appurl'));


		// register the resource name "db"
		$smarty->registerResource("ifexists", \_make('template/smartyifexists.php'));

		foreach ($viewFileList as $viewFile) {
			try {
				$smarty->display($viewFile.'.tpl');
				$this->logService->debug('Template: parsed file '.$viewFile.'.tpl');
				break;
			} catch (\SmartyException $e) {
				$this->logService->debug('Template: caught smarty exception: '.$e->getMessage());
				//don't return, try next template
			} catch (Exception $e) {
				$this->logService->error('Template: caught exception: '.$e->getMessage());
				return;
			}
		}
		return;
	}

	public function transformContent($content) {

		//struct
		if (is_array($content)) {
			return implode(' ', array_values($content));
		}

		//we have some special output,
		// could be text, could be object
		if (!is_object($content))
			return $content;

		//it's an object
		if (method_exists( $content, 'toHtml' )) {
			return call_user_func(array($content, 'toHtml'));
		}

		if (method_exists( $content, 'toString' )) {
			return call_user_func(array($content, 'toString'));
		}

		if (method_exists( $content, '__toString' )) {
			return call_user_func(array($content, '__toString'));
		}
	}
}

/*
function m_modal_open($args) {

extract($args);
return '
        <div class="modal" role="dialog" tabindex="-1" id="'.$id.'">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              </div>
              <form action="'.m_appurl($url).'" method="POST" data-async data-dialog="#'.$id.'">
              <div class="modal-body">
';
}

function m_modal_close($args) {
extract($args);
return '
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
               '.$buttons.'
              </div>
              </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
';
}
 */

