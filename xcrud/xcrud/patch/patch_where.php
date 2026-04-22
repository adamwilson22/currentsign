/** builds main where condition for query */
    protected function _build_where()
    {
        $db = Xcrud_db::get_instance($this->connection);
        $where_arr = array();
        $where_arr_pri = array();


        // user defined conditions
        if ($this->where)
        {
            foreach ($this->where as $key => $params)
            {
                if ($where_arr)
                    $where_arr[] = $params['glue'];

                if (!isset($params['custom']))
                {
                    $fieldkey = $this->_where_fieldkey($params);
                    if (is_array($params['value']))
                    {
                        $in_arr = array();
                        foreach ($params['value'] as $in_val)
                        {
                            $in_arr[] = $db->escape($in_val);
                        }
                        if (isset($this->subselect[$fieldkey]))
                        {
                            $where_arr[] = $this->subselect_where($fieldkey) . $this->_cond_from_where_in($params['field']) . '(' . implode(',', $in_arr) .
                                ')';
                        }
                        else
                        {
                            $where_arr[] = $this->_where_field($params) . $this->_cond_from_where_in($params['field']) . '(' . implode(',', $in_arr) .
                                ')';
                        }
                    }
                    else
                    {
                        if (isset($this->subselect[$fieldkey]))
                        {
                            $where_arr[] = $this->subselect_where($fieldkey) . $this->_cond_from_where($params['field']) . $db->escape($params['value'],
                                isset($this->no_quotes[$fieldkey]));
                        }
                        elseif (isset($this->point_field[$fieldkey]))
                        {
                            $where_arr[] = 'CONCAT(X(`' . $this->_where_field($params) . '`),\',\',Y(`' . $this->_where_field($params) . '`))' . $this->
                                _cond_from_where($params['field']) . $db->escape($params['value'], isset($this->no_quotes[$fieldkey]));
                        }
                        else
                        {
                            $where_arr[] = $this->_where_field($params) . $this->_cond_from_where($params['field']) . $db->escape($params['value'],
                                isset($this->no_quotes[$fieldkey]));
                        }
                    }
                }
                else
                {
                    $where_arr[] = '(' . $params['custom'] . ')';
                }
            }
        }

        // internal condition
        if ($this->where_pri)
        {
            foreach ($this->where_pri as $params)
            {
                if ($where_arr_pri)
                    $where_arr_pri[] = $params['glue'];
                if (isset($params['custom']))
                {
                    $where_arr_pri[] = '(' . $params['custom'] . ')';
                }
                else
                {
                    $where_arr_pri[] = $this->_where_field($params) . $this->_cond_from_where($params['field']) . $db->escape($params['value']);
                }
            }
        }

        // search condition
        if ($this->search && ($this->task == 'list' or $this->task == 'print' or $this->task == 'csv' or $this->after == 'list'))
        {
            if ($where_arr)
            {
                $where_arr[] = 'AND';
            }
            $search_columns = $this->search_columns ? $this->search_columns : $this->columns;
            if ($this->column && isset($search_columns[$this->column]))
            {
                // if relation
                if (isset($this->relation[$this->column]))
                {
                    $where_arr[] = $this->_build_relation_subwhere($this->column);
                }
                // if fk-relation
                elseif (isset($this->fk_relation[$this->column]))
                {
                    $where_arr[] = $this->_build_fk_relation_subwhere($this->column);
                }
                // search in subselect
                elseif (isset($this->subselect[$this->column]))
                {
                    $where_arr[] = '(' . $this->subselect_query[$this->column] . ') LIKE ' . $db->escape_like($this->phrase, $this->
                        search_pattern);
                }
                elseif (isset($this->point_field[$this->column]))
                {
                    $fdata = $this->_parse_field_names($this->column, 'build_where', false, false);
                    $fitem = reset($fdata);
                    $where_arr[] = 'CONCAT(X(`' . $fitem['table'] . '`.`' . $fitem['field'] . '`),\',\',Y(`' . $fitem['table'] . '`.`' . $fitem['field'] .
                        '`))LIKE ' . $db->escape_like($this->phrase, $this->search_pattern);
                }
                else
                {
                    $fdata = $this->_parse_field_names($this->column, 'build_where', false, false);
                    $fitem = reset($fdata);
                    $key = key($fdata);
                    // search via fild types
                    switch ($this->field_type[$this->column])
                    {
                        case 'timestamp':
                        case 'datetime':
                        case 'date':
                        case 'time':
                            switch ($this->field_type[$this->column])
                            {
                                case 'date':
                                    $format = 'Y-m-d';
                                    break;
                                case 'time':
                                    $format = 'H:i:s';
                                    break;
                                default:
                                    $format = 'Y-m-d H:i:s';
                                    break;
                            }
                            if ($this->phrase['from'] && $this->phrase['to'])
                            {
                                $where_arr[] = '(`' . $fitem['table'] . '`.`' . $fitem['field'] . '` BETWEEN ' . $db->escape(gmdate($format, (int)$this->
                                    phrase['from'])) . ' AND ' . $db->escape(gmdate($format, (int)$this->phrase['to'])) . ')';
                            }
                            elseif ($this->phrase['from'])
                            {
                                $where_arr[] = '(`' . $fitem['table'] . '`.`' . $fitem['field'] . '` >= ' . $db->escape(gmdate($format, (int)$this->
                                    phrase['from'])) . ')';
                            }
                            elseif ($this->phrase['to'])
                            {
                                $where_arr[] = '(`' . $fitem['table'] . '`.`' . $fitem['field'] . '` <= ' . $db->escape(gmdate($format, (int)$this->
                                    phrase['to'])) . ')';
                            }
                            break;
                        case 'select':
                        case 'radio':
                            $where_arr[] = '(`' . $fitem['table'] . '`.`' . $fitem['field'] . '` = ' . $db->escape($this->phrase) . ')';
                            break;
                            /*case 'multiselect':
                            case 'checkboxes':

                            break;*/
                        case 'bool':
                            if (isset($this->bit_field[$key]))
                            {
                                $where_arr[] = 'CAST(`' . $fitem['table'] . '`.`' . $fitem['field'] . '` AS UNSIGNED) = ' . ((int)$this->phrase);
                            }
                            else
                            {
                                $where_arr[] = '(`' . $fitem['table'] . '`.`' . $fitem['field'] . '` = ' . ((int)$this->phrase) . ')';
                            }
                            break;
                        default:
                            if (isset($this->point_field[$key]))
                            {
                                $where_arr[] = 'CONCAT(X(`' . $fitem['table'] . '`.`' . $fitem['field'] . '`),\',\',Y(`' . $fitem['table'] . '`.`' . $fitem['field'] .
                                    '`)) LIKE ' . $db->escape_like($this->phrase, $this->search_pattern);
                            }
                            elseif (isset($this->bit_field[$key]))
                            {
                                $where_arr[] = 'CAST(`' . $fitem['table'] . '`.`' . $fitem['field'] . '` AS UNSIGNED) LIKE ' . $db->escape_like($this->
                                    phrase, $this->search_pattern);
                            }
                            else
                            {
                                $where_arr[] = '(`' . $fitem['table'] . '`.`' . $fitem['field'] . '` LIKE ' . $db->escape_like($this->phrase, $this->
                                    search_pattern) . ')';
                            }
                            break;
                    }
                }
            }
            else
            {
                // multicolumn search
                //$f_array = array();
                $or_array = array();
                //$search_columns = $this->search_columns ? $this->search_columns : $this->columns;
                foreach ($search_columns as $key => $fitem)
                {
                    if (isset($this->relation[$key]))
                    {
                        $or_array[] = $this->_build_relation_subwhere($key);
                    }
                    elseif (isset($this->fk_relation[$key]))
                    {
                        $or_array[] = $this->_build_fk_relation_subwhere($key);
                    }
                    elseif (isset($this->subselect[$key]))
                    {
                        $or_array[] = '(' . $this->subselect_query[$key] . ') LIKE ' . $db->escape_like($this->phrase, $this->search_pattern);
                    }
                    elseif ($this->field_type[$key] == 'date' || $this->field_type[$key] == 'datetime' || $this->field_type[$key] ==
                        'timestamp' || $this->field_type[$key] == 'time')
                    {
                        if (preg_match('/^[0-9\-\:\s]+$/', $this->phrase))
                        {
                            $or_array[] = '`' . $fitem['table'] . '`.`' . $fitem['field'] . '` LIKE ' . $db->escape_like($this->phrase, $this->
                                search_pattern);
                        }
                    }
                    elseif (isset($this->point_field[$key]))
                    {
                        $or_array[] = 'CONCAT(X(`' . $fitem['table'] . '`.`' . $fitem['field'] . '`),\',\',Y(`' . $fitem['table'] . '`.`' . $fitem['field'] .
                            '`)) LIKE ' . $db->escape_like($this->phrase, $this->search_pattern);
                    }
                    elseif (isset($this->bit_field[$key]))
                    {
                        $or_array[] = 'CAST(`' . $fitem['table'] . '`.`' . $fitem['field'] . '` AS UNSIGNED) LIKE ' . $db->escape_like($this->
                            phrase, $this->search_pattern);
                    }
                    else
                    {
                        //$f_array[] = '`' . $fitem['table'] . '`.`' . $fitem['field'] . '`';
                        $or_array[] = '`' . $fitem['table'] . '`.`' . $fitem['field'] . '` LIKE ' . $db->escape_like($this->phrase, $this->
                            search_pattern);
                    }
                }
                $where = '(';
                /*if ($f_array)
                {
                $where .= 'CONCAT_WS(\' \',' . implode(',', $f_array) . ') LIKE ' . $db->escape_like($this->phrase, $this->
                search_pattern);
                }
                if ($f_array && $or_array)
                {
                $where .= ' OR ';
                }*/
                if ($or_array)
                {
                    $where .= implode(' OR ', $or_array);
                }
                $where .= ')';
                $where_arr[] = $where;
            }

        }
        
		//check for empty values
		foreach ($where_arr as $key => $value) {
		    if (empty($value) or $value == "()") {
		       unset($where_arr[$key]);
		    }
		}
		
		//check for empty values
		foreach ($where_arr_pri as $key => $value) {
		    if (empty($value) or $value == "()") {
		       unset($where_arr_pri[$key]);
		    }
		}
		
        // final part
        if ((!empty($where_arr)) && empty($where_arr_pri)){
        	return 'WHERE ' . ($where_arr ? '(' . implode(' ', $where_arr) . ')' : '');
        }else if(empty($where_arr) && (!empty($where_arr_pri))){
        	
            return 'WHERE ' . ($where_arr_pri ? ($where_arr ? ' AND ' :
                '') . implode(' ', $where_arr_pri) : '');
        }else if((!empty($where_arr)) && (!empty($where_arr_pri))){        	
             return 'WHERE ' . ($where_arr ? '(' . implode(' ', $where_arr) . ')' : '') . ($where_arr_pri ? ($where_arr ? ' AND ' :
                '') . implode(' ', $where_arr_pri) : '');
        }else{
        	 return '';
        }
           
    }