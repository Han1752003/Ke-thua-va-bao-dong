<?php
class StopWatch {
    private $startTime;
    private $endTime;

    public function __construct() {
        $this->startTime = microtime(true);
    }
    public function start() {
        $this->startTime = microtime(true);
    }
    public function stop() {
        $this->endTime = microtime(true);
    }
    public function getStartTime() {
        return $this->startTime;
    }
    public function getEndTime() {
        return $this->endTime;
    }
    public function getElapsedTime() {
        return $this->endTime - $this->startTime;
    }
}

$n = 10000;
$arr = range(1, $n);
shuffle($arr);

$stopwatch = new StopWatch();

$stopwatch->start();
for ($i = 0; $i < $n - 1; $i++) {
    $minIndex = $i;
    for ($j = $i + 1; $j < $n; $j++) {
        if ($arr[$j] < $arr[$minIndex]) {
            $minIndex = $j;
        }
    }
    $temp = $arr[$i];
    $arr[$i] = $arr[$minIndex];
    $arr[$minIndex] = $temp;
}
$stopwatch->stop();

$elapsedTime = $stopwatch->getElapsedTime() * 1000; //
echo "Thời gian thực thi của thuật toán sắp xếp chọn (selection sort) với $n phần tử: " . $elapsedTime . " milliseconds" . PHP_EOL;

?>