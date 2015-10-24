<?php

/**
 * BondIdeaApplication.
 *
 * @author Keiji Takano <takano@se-project.co.jp>
 */
class BondIdeaApplication extends Application
{

    /**
     * @return string
     */
    public function getRootDir()
    {
        return dirname(__FILE__);
    }

    /**
     * @return array
     */
    protected function registerRoutes()
    {
        return array(
          '/'
          => array('controller' => 'idea', 'action' => 'index'),
          '/idea/index'
          => array('controller' => 'idea', 'action' => 'index'),
          '/idea/register'
          => array('controller' => 'idea', 'action' => 'register'),
          '/console/getKizapiKeyword'
          => array('controller' => 'api', 'action' => 'getKizapiKeyword'),
        );
    }

    /**
     *
     */
    protected function configure()
    {
        $this->db_manager->connect('master', array(
          'dsn'      => 'mysql:dbname=bond_idea;host=localhost',
          'user'     => 'bond_admin',
          'password' => 'bondpass',
        ));
    }
}
