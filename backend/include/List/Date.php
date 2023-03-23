<?php

namespace List;

class Date extends AbstractList
{
	/** @var array<string, boolean|string> $settings */
	protected array $settings = [
		'calculateMostBanned' => false,
		'orderBy' => 'date'
	];

	/**
	 * Add IP address
	 * 
	 * @param array<string, mixed> $ip IP address details
	 */
	public function addIp(array $ip): void
	{
		$date = date('Y-m-d', strtotime($ip['timestamp']));
		$key = array_search($date, array_column($this->data['list'], 'date'));

		if ($key === false) {
			$this->data['list'][] = [
				'date' => $date,
				'bans' => 1,
				'ipCount' => 1,
				'ipList' => [$ip['address']]
			];
		} else {
			$this->data['list'][$key]['bans']++;

			if (in_array($ip['address'], $this->data['list'][$key]['ipList']) === false) {
				$this->data['list'][$key]['ipList'][] = $ip['address'];
				$this->data['list'][$key]['ipCount']++;
			}
		}
	}
}