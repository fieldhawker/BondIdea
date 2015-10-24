<?php

/**
 * IdeaController.
 *
 * @author Keiji Takano <takano@se-project.co.jp>
 */
class IdeaController extends Controller
{

    const LOG_FORMAT = "%s %s\n %s %s %s (%d)\n=====\n";

    /**
     * @return string
     */
    public function indexAction()
    {

        return $this->render(array(
          '_token' => $this->generateCsrfToken('idea/index'),
        ), null);

    }

    /**
     * @return string|void
     */
    public function registerAction()
    {

        $modifiers = array();

        if (!$this->request->isPost()) {
            return $this->redirect('/');
//            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('idea/index', $token)) {
            return $this->redirect('/');
        }

        $keyword = trim($this->request->getPost('keyword'));
        $this->log->addInfo(
          sprintf(self::LOG_FORMAT, $this->finger, var_export(
            $keyword, 1), date(DATE_RFC822), __FILE__, __METHOD__, __LINE__)
        );

        $errors = $this->db_manager->get('Ideas')->validInsert($keyword);

        if (count($errors) === 0) {

            $modifiers = $this->db_manager->get('Ideas')->fetchRndKeyword();

            if (!$this->db_manager->get('Ideas')->isDuplicateKeyword($keyword)) {
                try {
                    $this->db_manager->get('Ideas')->insert($keyword);

                    $this->log->addInfo(
                      sprintf(self::LOG_FORMAT, $this->finger, var_export(
                        $keyword, 1), date(DATE_RFC822), __FILE__, __METHOD__, __LINE__)
                    );

                } catch (Exception $e) {

                    $this->log->addInfo(
                      sprintf(self::LOG_FORMAT, $this->finger, var_export(
                        $e->getMessage(), 1), date(DATE_RFC822), __FILE__, __METHOD__, __LINE__)
                    );
                }
            }

        }

        return $this->render(array(
          'errors'    => $errors,
          'modifiers' => $modifiers,
          'keyword'   => $keyword,
          '_token'    => $this->generateCsrfToken('idea/index'),
        ), 'register');

    }

}
