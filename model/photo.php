<?php
	class Photo{		

		private $id;
		private $number;
		private $text;
		private $date;
		private $image;
				
		function __construct($id, $number, $text, $date, $image){
			$this->setId($id);
			$this->setNumber($number);
			$this->setText($text);
			$this->setDate($date);
			$this->setImage($image);
			}		
		
		public function getNumber(){
			return $this->number;
		}
		
		public function setNumber($number){
			$this->number = $number;
		}
		
		public function getText(){
			return $this->text;
		}
		
		public function setText($text){
			$this->text = $text;
		}

		public function getDate(){
			return $this->date;
		}

		public function setDate($date){
			$this->date = $date;
		}

		public function getImage(){
			return $this->image;
		}

		public function setImage($image){
			$this->image = $image;
		}

		public function setId($id){
			$this->id = $id;
		}

		public function getId(){
			return $this->id;
		}

	}
?>