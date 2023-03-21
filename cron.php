<?php
date_default_timezone_set("Asia/Shanghai");
class Database
{
    protected $pdo;
    private $dsn = "mysql:dbname=test;host=localhost";
    private $username = "root";
    private $password = "";

    /**
     * Database constructor.
     *
     * @param string $dsn The DSN string.
     * @param string $username The username to connect to the database.
     * @param string $password The password to connect to the database.
     * @param array $options An array of PDO options.
     */
    public function __construct(array $options = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC])
    {
        $dsn = $this->dsn;
        $username = $this->username;
        $password = $this->password;
        $this->pdo = new PDO($dsn, $username, $password, $options);
    }

    /**
     * Executes a SQL query and returns the result set as an array of objects.
     *
     * @param string $query The SQL query to execute.
     * @param array $params An array of parameters to bind to the query.
     * @return array An array of objects representing the rows returned by the query.
     */
    public function query(string $query, array $params = []): array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }




    /**
     * Executes a SQL query and returns the first row as an object.
     *
     * @param string $query The SQL query to execute.
     * @param array $params An array of parameters to bind to the query.
     * @return object|null The first row returned by the query, or null if no rows were returned.
     */
    public function queryOne(string $query, array $params = []): ?object
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_OBJ) ?: null;
    }

    /**
     * Executes a SQL query and returns the value of the first column of the first row.
     *
     * @param string $query The SQL query to execute.
     * @param array $params An array of parameters to bind to the query.
     * @return mixed The value of the first column of the first row returned by the query.
     */
    public function queryScalar(string $query, array $params = [])
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    /**
     * Executes a SQL query and returns the number of rows affected.
     *
     * @param string $query The SQL query to execute.
     * @param array $params An array of parameters to bind to the query.
     * @return int The number of rows affected by the query.
     */
    public function execute(string $query, array $params = []): int
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * Begins a transaction.
     */
    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    /**
     * Commits a transaction.
     */
    public function commit()
    {
        $this->pdo->commit();
    }

    /**
     * Rolls back a transaction.
     */
    public function rollBack()
    {
        $this->pdo->rollBack();
    }
}



class Cron
{
    private array $nextDrawData = [];
    private array $nextTwoDraws;
    private $conn;
    private const DEFAULT_INTERVAL = 60; //seconds
    private const GAP_INTERVAL = 3600; // seconds
    private const TOTAL_RANDOM_NUMBERS = 5;
    private const GAME_START = "00:00:00";
    private const GAME_END = "24:00:00";
    private const GAP_START = "04:59:00"; //1 hour gap
    private const GAP_END = "06:00:00";
    private array $dataToSend;


    public function __construct()
    {
        $this->conn = new Database();
        $this->setNextDrawData();
        // echo $this->getNextTwoDrawsSecs();
    }


    function generateRandomNumbers($total = 5, $min = 0, $max = 9)
    {
        $result = [];
        for ($i = 0; $i < $total; $i++)
            array_push($result, random_int($min, $max));
        return $result;
    }

    /**
     * the number of seconds between 12 am today and the @$time provided
     *
     * @param string $time the time to calculate the number of seconds up to.
     * @param string $start where to start calculating difference. default is 12 am today
     * @return string the difference in seconds
     */
    function getSecondsElapsed($time, $start = "today")
    {
        $dateTime = DateTime::createFromFormat("H:i:s", $time);
        $timeElapsed = $dateTime->getTimestamp() - strtotime($start);
        return $timeElapsed;
    }

    /**
     * gets the next draw datetime that comes immediately after the "$time" provided
     *
     * @param string  $time the datetime or time to start calculating from.
     * @return string the next closest draw datetime to the "$time" provided
     */
    function getNextDrawTime($time)
    {
        $time = Date("H:i:s", strtotime($time));
        //if the time requested is still between the gap ie if gap hasn't ended.
        if (($this::GAP_START and $this::GAP_END) and $time >= $this::GAP_START and $time < $this::GAP_END) {
            return date("Y-m-d") . ' ' . $this::GAP_END;
        }

        //if the last data has been drawn
        if ($time >=  $this::GAME_END) {
            $nextDay = strtotime("+$this::DEFAULT_INTERVAL seconds", strtotime($this::GAME_END));
            return date("Y-m-d", $nextDay) . ' ' . $this::GAME_START;
        }

        //round the time up to the nearest draw time
        $timeElapsed = $this->getSecondsElapsed($time, $this::GAME_START);
        $surplusSeconds = $timeElapsed % $this::DEFAULT_INTERVAL;
        $nextDrawInSecs = $timeElapsed - $surplusSeconds + $this::DEFAULT_INTERVAL;
        $nextDrawTimestamp = $nextDrawInSecs + strtotime($this::GAME_START);
        return date("Y-m-d H:i:s", $nextDrawTimestamp);
    }

    /**
     * sets the information about the next draw. 
     * information is in an array with keys draw_count and draw_time
     *
     * @return void
     */
    public function setNextDrawData()
    {
        $currentTime = Date('H:i:s');
        $this->dataToSend["requestTime"] = $currentTime;
        $aboutToDrawDatetime = $this->getNextDrawTime($currentTime); //gets next draw time based on current time.
        $this->dataToSend["aboutToDrawDatetime"] = $aboutToDrawDatetime;
        $aboutToDrawData = explode(" ", $aboutToDrawDatetime);
        $aboutToDrawHIS = $aboutToDrawData[1];
        $SQL = "SELECT draw_id AS draw_count, draw_time FROM 1k1min WHERE draw_time = ? LIMIT 1";
        $results = $this->conn->query($SQL, [$aboutToDrawHIS]);
        $this->dataToSend["SQL_Results"] = $results;
        $nextDraw = $results[0];
        $nextDraw["draw_time"] = $aboutToDrawDatetime;
        $nextDraw['draw_date'] = Date('Ymd') . str_pad($nextDraw['draw_count'],  4, "0", STR_PAD_LEFT);
        $nextDraw['draw_numbers'] = $this->generateRandomNumbers();
        $this->dataToSend["dataInserted"] = $nextDraw;
        $this->nextDrawData = $nextDraw;
    }

    /**
     * gets the difference between the current draw and the next draw after that.
     *
     * @return void
     */
    public function getNextTwoDrawsSecs()
    {
        $nextDrawTime = $this->getNextDrawTime($this->nextDrawData['draw_time']);
        $justDrawTime = $this->nextDrawData['draw_time'];
        return $this->getDifferenceInSecs($nextDrawTime, $justDrawTime);
    }



    /**
     * inserts the current draw details into the database
     *
     * @return void
     */
    public function insertDrawDetails()
    {
        $draw_date = $this->nextDrawData['draw_date'];
        $SQL = "SELECT COUNT(draw_date) FROM royal5draw WHERE draw_date = ?";
        $dataExists = $this->conn->queryScalar($SQL, [$draw_date]);
        if (!$dataExists) {
            $SQL = "INSERT INTO royal5draw(draw_count, draw_date, draw_time, draw_number, draw_datetime) VALUES (?, ?, ?, ?, ?)";
            $draw_time =  $this->nextDrawData['draw_time'];
            $draw_count = $this->nextDrawData['draw_count'];
            $draw_numbers =  implode(',', $this->nextDrawData['draw_numbers']);
            $draw_datetime = Date('Y-m-d H:i:s');
            // echo json_encode([$draw_id, $draw_date, $draw_numbers, $draw_datetime]);
            // die;
            $this->conn->query($SQL, [$draw_count, $draw_date, $draw_time, $draw_numbers, $draw_datetime]);
        }
    }

    /**
     * gets the number of seconds between two dates.
     * this function only works if the difference in dates does not exceed 30 days;
     *
     * @param string $time1 the first date time
     * @param mixed $time2 the second date time. if this isn't provided, current datetime will be used.
     * @return int the number of seconds between the two dates provided.
     */
    public function getDifferenceInSecs($time1, $time2 = true)
    {
        $start = new DateTime($time1);
        $end = $time2 === true ? new DateTime() : new DateTime($time2);
        $diff = $start->diff($end);
        $diffInSecs = $diff->d * 24 * 60 * 60 + $diff->h * 60 * 60 + $diff->i * 60 + $diff->s;
        return $diffInSecs;
    }


    /**
     * gets the seconds before the next draw
     *
     * @return int
     */
    public  function getSecondsUntilNextDraw()
    {
        return $this->getDifferenceInSecs($this->nextDrawData['draw_time']);
    }


    function startCron()
    {
        $delay = $this->getSecondsUntilNextDraw();
        $this->dataToSend["delay"] = $delay;
        $response["nextRequestTime"] = $this->getSecondsUntilNextDraw();

        //if waiting time is more than 1 minute then send data to client telling when to make the next request.
        if ($delay <= 60) {
            sleep($delay);
            $this->insertDrawDetails();
            $response["nextRequestTime"] = $this->getNextTwoDrawsSecs();
        }
        $response["logs"] = $this->dataToSend;
        echo json_encode($response);
        die;
    }
}

$cron = new Cron();
$cron->startCron();
