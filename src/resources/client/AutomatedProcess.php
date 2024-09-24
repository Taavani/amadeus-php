<?php
/**
 * This file contains a class to represent an automated process in the Amadeus API.
 *
 * Class AutomatedProcess
 * @namespace Amadeus\Resources\Client
 */

namespace Amadeus\Resources\Client;

const IMMEDIATE = 'IMMEDIATE';
const DELAYED = 'DELAYED';
const ERROR = 'ERROR';

/**
 * Class AutomatedProcess
 * @package Amadeus\Resources\Client
 */
class AutomatedProcess
{
	private string $code;
	// Identifies the queue onto which PNR must be automatically placed upon process execution.
	private ?Queue $queue;
	private string $text;
	private ?string $delay;
	private string $officeId;
	private string $dateTime;

	/**
	 * AutomatedProcess constructor.
	 */
	public function __construct() {
		$this->code = IMMEDIATE;
		$this->queue = null;
		$this->text = '';
		$this->delay = '';
		$this->officeId = '';
		$this->dateTime = '';
	}

	/**
	 * Set the code to immediate.
	 *
	 * @return AutomatedProcess
	 */
	public function setCodeToImmediate(): AutomatedProcess {
		$this->code = IMMEDIATE;
		return $this;
	}

	/**
	 * Set the code to delayed.
	 *
	 * @return AutomatedProcess
	 */
	public function setCodeToDelayed(): AutomatedProcess {
		$this->code = DELAYED;
		return $this;
	}

	/**
	 * Set the code to error.
	 *
	 * @return AutomatedProcess
	 */
	public function setCodeToError(): AutomatedProcess {
		$this->code = ERROR;
		return $this;
	}

	/**
	 * Set the queue.
	 *
	 * @param Queue $queue
	 * @return AutomatedProcess
	 */
	public function setQueue(Queue $queue): AutomatedProcess {
		$this->queue = $queue;
		return $this;
	}

	/**
	 * Set the text.
	 *
	 * @param string $text
	 * @return AutomatedProcess
	 */
	public function setText(string $text): AutomatedProcess {
		$this->text = $text;
		return $this;
	}

	/**
	 * Set the delay.
	 *
	 * @param ?string $delay
	 * @return AutomatedProcess
	 */
	public function setDelay(?string $delay): AutomatedProcess {
		$this->delay = $delay;
		return $this;
	}

	/**
	 * Set the office ID.
	 *
	 * @param string $officeId
	 * @return AutomatedProcess
	 */
	public function setOfficeId(string $officeId): AutomatedProcess {
		$this->officeId = $officeId;
		return $this;
	}

	/**
	 * Set the date and time.
	 *
	 * @param string $dateTime
	 * @return AutomatedProcess
	 */
	public function setDateTime(string $dateTime): AutomatedProcess {
		$this->dateTime = $dateTime;
		return $this;
	}

	/**
	 * Convert the object to an array.
	 *
	 * @return array
	 */
	public function __toArray(): array {
		$data = [];

		$data['code'] = $this->code;
		if ($this->queue) {
			$data['queue'] = $this->queue->__toArray();
		}

		if ($this->text) {
			$data['text'] = $this->text;
		}

		if ($this->delay) {
			$data['delay'] = $this->delay;
		}

		if ($this->officeId) {
			$data['officeId'] = $this->officeId;
		}

		if ($this->dateTime) {
			$data['dateTime'] = $this->dateTime;
		}

		return $data;
	}
}