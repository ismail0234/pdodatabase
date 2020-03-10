<?php 

namespace IS\QueryBuilder\Database\Helper;

use IS\QueryBuilder\Database\Utility\Utility;

Class Database
{

    /**
     *
     * Yapılacak sql sorgusunun yapısını ve değerlerini barındırır.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @var array $sqlData
     *
     */
    public $sqlData = array(); 

	/**
	 *
	 * Veritabanı ayarlarını tutar.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @var array $config
	 *
	 */
	protected $config;

	/**
	 *
	 * Tablo öneki(prefix) değerini saklar.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @var string $prefix
	 *
	 */
	public $prefix;

	/**
	 *
	 * Veritabanı bağlantısını içerir.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 *
	 */
	protected $db;

	/**
	 *
	 * Sql sorgularının loglarını tutar.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @var array $debug
	 *
	 */
	public $debug = array();

    /**
     *
     * Kalıcı Bağlantılar için en son bağlantı zamanı
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @var int $persistentTime
     *
     */
    protected $persistentTime;

	/**
	 *
	 * Veritabanı ayarlarını yapmak için sınıf oluşturucu içinde kullanılır.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param array $configuration
	 *
	 */
	public function __construct($configuration)
	{
		$this->config = Utility::inializeConfiguration($configuration);
		$this->prefix = $this->config['prefix'];
		$this->clearQuery();
		$this->connectDatabase();
	}	

    /**
     *
     * Veritabanı bağlantısını kurar.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     *
     */
	private function connectDatabase()
	{

        try
        {
            $this->db = new \PDO(Utility::connectionString($this->config), $this->config['username'], $this->config['password']);
            $this->db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->persistentTime = time();
        }
        catch(\Exception $e)
        {
        	Debug::sendException('Database Connection Failed', array(0 => '1049', 2 => $e->getMessage()), Utility::connectionString($this->config), $this->config['exception']);
        }

	}

	/**
	 *
	 * Hazırlanan sql sorgusunu çalıştırır ve sonucunu döndürür.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param string $sql
	 * @param array  $array
	 * @return
	 *
	 */
	public function execute($sql, $array = array(), $status = 1)
    {

        if ($this->config['persistent'] > 3 && $this->persistentTime + $this->config['persistent'] - 1 <= time() ) {
            $this->connectDatabase();
        }

        $this->clearQuery();
        
    	if ($this->config['querylog']) {
    		$start = microtime(1);
    	}

		$prepare   = $this->db->prepare($sql);
		$errorCode = $this->db->errorInfo();
        if($errorCode[0] > 0){
        	Debug::sendException('SQL Prepare Error', $errorCode, $sql, $this->config['exception']);
        }else if(!$prepare->execute($array)){
        	Debug::sendException('SQL Execute Error', $prepare->errorInfo(), $sql, $this->config['exception']);
        }

        $sonuc = true;

        switch($status)
        {

            case 1: $sonuc = $prepare->fetchAll(\PDO::FETCH_OBJ);  break;
            case 2: $sonuc = $prepare->fetchAll();  break;
            case 3: $sonuc = $prepare->fetch(\PDO::FETCH_OBJ);  break;
            case 4: $sonuc = $prepare->fetch();  break;
            case 5: $sonuc = $prepare->rowcount();  break;
            case 6: $sonuc = $this->db->lastInsertId();  break;

        }

        if ($this->config['querylog']) {
            $this->debugAdd($sql, $array, microtime(1) - $start);
        }

        return $sonuc;

    }

    /**
     *
     * Sql sorgularını birleştirir.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $data
     *
     */
    protected function migrateSQL($data)
    {
    	$this->sqlData = array_merge($this->sqlData, $data);
    }

    /**
     *
     * Log kayıtı ekler.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $sql
     * @param array $array
     * @param int $speed
     *
     */
	protected function debugAdd($sql, $array, $speed)
	{

        $this->debug[] = [
            'sql'   => $sql,
            'value' => $array,
            'speed' => $speed,
        ];

	}

    /**
     *
     * Hata ayıklamak için türünü değiştirmeye yarar.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param bool $type
     * @return bool $type 
     *
     */
    public function _setException($type)
    {
        $this->config['exception'] = $type === true;
    }

    /**
     *
     * Transaction işlemini başlatır ve debug türünü exception olarak ayarlar.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     *
     */
    public function _beginTransaction()
    {
        $this->_setException(true);
        $this->db->beginTransaction();
    }

    /**
     *
     * Sorgular eğer bir exception verirse rollback ile geri alınır.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     *
     */
    public function _rollBack()
    {
        $this->db->rollBack();
    }

    /**
     *
     * Sorgular üzerinde bir hata yok ise sorguların tamamı çalıştırılır.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     *
     */
    public function _commit()
    {
        $this->db->commit();
    }

    /**
     *
     * Mevcut sorguyu temizler.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     *
     */
    public function clearQuery()
    {

        $this->sqlData = array(
            'prefix'   => $this->config['prefix'],
            'select'   => array(),
            'table'    => array(),
            'where'    => array(),
            'value'    => array(),
            'set'      => array(),
            'orderby'  => array(),
            'groupby'  => array(),
            'join'     => array(),
            'having'   => array(
                'text'  => array() , 
                'value' => array()
            ),
            'special'   => array( 
                'text'  => array() , 
                'value' => array()
            ),
            'limit'       => array('text' => array()),
            'ignoreAlias' => array(),
        );

    }


}
