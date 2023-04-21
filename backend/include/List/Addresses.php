<?php

namespace List;

class Addresses extends AbstractList
{
    /** {@inheritDoc} */
    protected array $data = [
        'mostBanned' => '',
        'totalBans' => 0,
        'list' => []
    ];

    /** {@inheritDoc} */
    protected array $settings = [
        'calculateMostBanned' => true,
        'orderBy' => 'bans'
    ];

    /** {@inheritDoc} */
    public function addIp(array $ip): void
    {
        $key = array_search($ip['address'], array_column($this->data['list'], 'address'));

        if ($key === false) {
            $this->data['totalBans']++;
            $this->data['list'][] = [
                'address' => $ip['address'],
                'version' => $ip['version'],
                'bans' => 1,
                'country' =>  $ip['country']['code'],
                'continent' =>  $ip['continent']['code'],
                'network' => $ip['network']['number'],
                'subnet' => $ip['network']['subnet'],
                'events' => [[
                    'timestamp' => $ip['timestamp'],
                    'jail' => $ip['jail']
                ]]
            ];
        } else {
            $this->data['totalBans']++;
            $this->data['list'][$key]['bans']++;
            $this->data['list'][$key]['events'][] = [
                'timestamp' => $ip['timestamp'],
                'jail' => $ip['jail']
            ];
        }
    }

    /**
     * Order by date
     */
    protected function orderByDate(): void
    {
        $list = $this->data['list'];

        foreach ($list as $itemKey => $item) {
            usort($this->data['list'][$itemKey]['events'], function($a1, $a2) {
                $v1 = strtotime($a1['timestamp']);
                $v2 = strtotime($a2['timestamp']);
                return $v2 - $v1;
            });

            $this->data['list'][$itemKey]['firstSeen'] = $this->data['list'][$itemKey]['events'][0]['timestamp'];
        }

        usort($this->data['list'], function($a1, $a2) {
            $v1 = strtotime($a1['firstSeen']);
            $v2 = strtotime($a2['firstSeen']);
            return $v2 - $v1;
        });
    }
}