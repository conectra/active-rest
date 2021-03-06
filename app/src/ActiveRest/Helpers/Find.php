<?php
namespace ActiveRest\Helpers;

/**
 * Class Find
 *
 * @package Helpers\Request
 */
final class Find
{

    /**
     * @param array $params
     * @return array
     */
    public static function params($params = [])
    {
        // argumento arguments a ser utilizado no active record method find
        $arguments = [];

        // argumento options a ser utilizado no active record method find
        $options = [];

        if (empty($params)) {
            $options = [
                'orderBy' => [
                    'column'    => 'ID',
                    'direction' => 'DESC',
                ],
                'limit'   => [
                    'number' => 10,
                ],
            ];

            return [
                'options'   => $options,
                'arguments' => $arguments,
            ];
        }

        if (array_key_exists(
                'arguments',
                $params
            ) && is_array($params['arguments'])
        ) {
            $arguments = $params['arguments'];
        }

        if (array_key_exists(
            'options',
            $params
        )) {
            if (array_key_exists(
                'limit',
                $params['options']
            )) {
                // Valida limit e converte pra inteiro
                if (array_key_exists(
                        'number',
                        $params['options']['limit']
                    ) && is_numeric($params['options']['limit']['number'])
                ) {
                    // limit = 0 não limita registros
                    // Sem limit não usa Offset
                    if ($params['options']['limit']['number'] != 0) {
                        $options['limit']['number'] = (int)($params['options']['limit']['number']);
                        // Valida offset e converte pra inteiro
                        if (array_key_exists(
                                'offset',
                                $params['options']['limit']
                            ) && is_numeric($params['options']['limit']['offset'])
                        ) {
                            $options['limit']['offset'] = (int)($params['options']['limit']['offset']);
                        }
                    }
                }
            }

            if (array_key_exists(
                'orderBy',
                $params['options']
            )) {
                $params['options']['orderBy'] = count(
                    array_filter(
                        array_keys($params['options']['orderBy']),
                        'is_string'
                    )
                ) > 0 ? [$params['options']['orderBy']] : $params['options']['orderBy'];

                foreach ($params['options']['orderBy'] as $item) {
                    $optionItem = [];

                    // Valida limit e converte pra inteiro
                    if (array_key_exists(
                            'column',
                            $item
                        ) && is_string($item['column'])
                    ) {
                        $optionItem['column'] = $item['column'];
                    }

                    // Valida offset e converte pra inteiro
                    if (array_key_exists(
                            'direction',
                            $item
                        ) && is_string($item['direction'])
                    ) {
                        $optionItem['direction'] = $item['direction'];
                    }

                    if (!empty($optionItem)) {
                        $options['orderBy'][] = $optionItem;
                    }
                }
            }

            if (array_key_exists(
                'withDependencies',
                $params['options']
            )) {
                $options['withDependencies'] = $params['options']['withDependencies'];
            }

            if (array_key_exists(
                'withProperties',
                $params['options']
            )) {
                $options['withProperties'] = $params['options']['withProperties'];
            }
        }

        return [
            'options'   => $options,
            'arguments' => $arguments,
        ];
    }
}
