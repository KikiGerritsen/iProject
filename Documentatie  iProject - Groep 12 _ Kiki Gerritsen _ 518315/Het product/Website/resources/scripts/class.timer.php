<?php
class countdown {
	protected $year;
	protected $month;
	protected $day;
	protected $hour;
	protected $minute;
	protected $second;
	protected $format;
	function __construct($year=NULL, $month=NULL, $day=NULL, $hour=NULL, $minute=NULL, $second=NULL, $format=NULL) {
		if($year == NULL) { $this->year = date('Y'); } else { $this->year = $year; }
		if($month == NULL) { $this->month = date('m'); } else { $this->month = $month; }
		if($day == NULL) { $this->day = date('d'); } else { $this->day = $day; }
		if($hour == NULL) { $this->hour = 0; } else { $this->hour = $hour; }
		if($minute == NULL) { $this->minute = 0; } else { $this->minute = $minute; }
		if($second == NULL) { $this->second = 0; } else { $this->second = $second; }		
		if($format == NULL) { $this->format = '%y %m %d %h %m %s'; } else { $this->format = strtolower($format); }
	}
	public function setYear($year) {
		$this->year = $year;
	}
	protected function getYear() {
		return $this->year;
	}
	public function setMonth($month) {
		$this->month = $month;
	}
	protected function getMonth() {
		return $this->month;
	}
	public function setDay($day) {
		$this->day = $day;
	}
	protected function getDay() {
		return $this->day;
	}
	public function setHour($hour) {
		$this->hour = $hour;
	}
	protected function getHour() {
		return $this->hour;
	}
	public function setMinute($minute) {
		$this->minute = $minute;
	}
	protected function getMinute() {
		return $this->minute;
	}
	public function setsecond($second) {
		$this->second = $second;
	}
	protected function getSecond() {
		return $this->second;
	}
	public function setFormat($format) {
		$this->format = $format;
	}
	protected function getFormat() {
		return $this->format;
	}
	public function getTimeRemaining() {
		$start = time();
		$end = mktime($this->getHour(), $this->getMinute(), $this->getSecond(), $this->getMonth(), $this->getDay(), $this->getYear());
		$span = ($end-$start);
		if($span<0){
			$span=0;
		}

		if( strstr($this->format, '%d') ) {
			$oneDay = (60 * 60 * 24);
			$day = floor($span / $oneDay); 
			$span = $span % $oneDay; 

			$this->setFormat( str_replace('%d', $day, $this->getFormat()) );
		}
		if( strstr($this->format, '%h') ) {
			$oneHour = (60 * 60);
			$hour = floor($span / $oneHour); 
			$span = $span % $oneHour;
			
			$this->setFormat( str_replace('%h', $hour, $this->getFormat()) );
		}
		if( strstr($this->format, '%m') ) {
			$minute = floor($span / 60);
		
			$this->setFormat( str_replace('%m', $minute, $this->getFormat()) );
		}
		if( strstr($this->format, '%s') ) {
			$second = $span % 60;
			
			$this->setFormat( str_replace('%s', $second, $this->getFormat()) );
		}
		return $this->getFormat();
	}
}
?>