<?php

namespace App\Repository;

require_once 'Infrastructure/sparqllib.php';

class SparqlRepository
{
    function execute($sparql = '', $reasoner = '1')
    {
        $prefix = '	PREFIX data: <http://www.semanticweb.org/qnf/ontologies/2015/7/untitled-ontology-10#>
                    PREFIX owl:  <http://www.w3.org/2002/07/owl#>
                    PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> ';

        $url = ($reasoner > 0) ? 'http://localhost:3030/ds/sparql' : 'http://localhost:3030/ds/sparql';

        $db = sparql_connect($url);
        if ( ! $db) {
            echo sparql_errno() . ": " . sparql_error() . "\n";
            exit;
        }

        $result = sparql_query($prefix . $sparql);
        if ( ! $result) {
            print sparql_errno() . ": " . sparql_error() . "\n";
            exit;
        }

        $fields = $result->field_array($result);
        $return = array();
        while ($row = sparql_fetch_array($result)) {
            foreach ($fields as $field) {
                if ( ! preg_match('/owl/', $row[$field]) && ! preg_match('/rdf-schema/', $row[$field]))
                    $return[] = str_replace('http://www.semanticweb.org/qnf/ontologies/2015/7/untitled-ontology-10#', '', $row[$field]);
            }
        }

        return $return;
    }

    function get_individu($class)
    {
        $sparql = 'SELECT ?ind WHERE { ?ind rdf:type data:' . $class . ' }';

        return $this->execute($sparql);
    }

    function get_clas($class)
    {
        $sparql = 'SELECT ?ind WHERE { ?ind rdfs:subClassOf data:' . $class . ' }';

        return $this->execute($sparql);
    }

    function lower_class($var)
    {
        $sparql = 'SELECT ?superclass WHERE { data:' . $var . ' rdfs:subClassOf ?superclass }';

        return $this->execute($sparql, 0);
    }

    function lower_individu($var)
    {
        $sparql = 'SELECT ?superclass WHERE { data:' . $var . ' rdf:type ?superclass }';

        return $this->execute($sparql, 0);
    }

    function supp_by($var)
    {
        $sparql = 'SELECT ?spec WHERE { data:' . $var . ' data:suppBy ?spec }';

        return $this->execute($sparql, 1);
    }

    function getAllFunctionalRequirements() {
        $sparql = 'SELECT ?funct WHERE { ?funct rdfs:subClassOf data:Funcreq }';

        return $this->execute($sparql, 0);
    }

    function getSubclassOf($funcReq) {
        $sparql = 'SELECT ?subclass WHERE { ?subclass rdfs:subClassOf data:' . $funcReq . ' }';

        return $this->execute($sparql, 0);
    }

    function getIndividuals($obj) {
        $sparql = 'SELECT ?ind WHERE { ?ind a owl:NamedIndividual , data:' . $obj . ' }';

        return $this->execute($sparql, 0);
    }

    function getTreeFromFunc($funcReqs) {
        return $this->getFuncTree($funcReqs);
    }

    function getTreeFromAllFunc() {
        return $this->getFuncTree($this->getAllFunctionalRequirements());
    }

    function getIndividualsFromFunc($res) {
        $arr = [];

        foreach ($res as $r) {
            $subclasses = $this->getSubclassOf($r);
            if (count($subclasses)) {
                foreach ($subclasses as $subclass) {
                    $individuals = $this->getIndividuals($subclass);

                    if (count($individuals)) {
                        $arr = array_merge($arr, $individuals);
                    }
                }
            } else {
                $individuals = $this->getIndividuals($r);
                $arr = array_merge($arr, $individuals);
            }
        }

        return $arr;
    }

    function getIndividualsFromFunc2($res) {
        $arr = [];

        foreach ($res as $r) {
            $subclasses = $this->getSubclassOf($r);
            if (count($subclasses)) {
                foreach ($subclasses as $subclass) {
                    $individuals = $this->getIndividuals($subclass);

                    if (count($individuals)) {
                        $arr[$r] = $individuals;
                    }
                }
            } else {
                $individuals = $this->getIndividuals($r);
                $arr[$r] = $individuals;
            }
        }

        return $arr;
    }

    private function getFuncTree($res) {
        $arr = [];

        foreach ($res as $r) {
            $subclasses = $this->getSubclassOf($r);
            if (count($subclasses)) {
                foreach ($subclasses as $subclass) {
                    $individuals = $this->getIndividuals($subclass);

                    if (count($individuals)) {
                        $arr[$r]['subclasses'][$subclass]['individuals'] = $individuals;
                    }
                }
            } else {
                $individuals = $this->getIndividuals($r);
                $arr[$r]['individuals'] = $individuals;
            }
        }

        return $arr;
    }

    function getProductsFromFuncIndividual($funcIndividual) {
        $suppQuery = 'SELECT ?supp WHERE { data:' . $funcIndividual . ' data:SuppBy ?supp }';
        $suppBy = array_map(function ($supp) {
            return 'data:' . $supp;
        }, $this->execute($suppQuery));

        $getProductsFromSuppQuery = 'SELECT ?laptop WHERE { ?laptop data:hasSpec ' . implode(',', $suppBy) . ' }';
        return $this->execute($getProductsFromSuppQuery);
    }

    function hasSpec($laptop)
    {
        $sparql = 'SELECT ?spec WHERE { data:' . $laptop . ' data:hasSpec ?spec }';

        return $this->execute($sparql, 1);
    }

    function hasBattery($laptop)
    {
        $sparql = 'SELECT ?battery WHERE { data:' . $laptop . ' data:hasBattery ?battery }';

        return $this->execute($sparql, 1);
    }

    function hasBrand($laptop)
    {
        $sparql = 'SELECT ?brand WHERE { data:' . $laptop . ' data:hasBrand ?brand }';

        return $this->execute($sparql, 1);
    }

    function hasCamera($laptop)
    {
        $sparql = 'SELECT ?camera WHERE { data:' . $laptop . ' data:hasCamera ?camera }';

        return $this->execute($sparql, 1);
    }

    function hasDaya_Baterai($laptop)
    {
        $sparql = 'SELECT ?daya_baterai WHERE { data:' . $laptop . ' data:hasDaya_Baterai ?daya_baterai }';

        return $this->execute($sparql, 1);
    }

    function hasDetails($laptop)
    {
        $sparql = 'SELECT ?details WHERE { data:' . $laptop . ' data:hasDetails ?details }';

        return $this->execute($sparql, 1);
    }

    function get_brand_os($pref)
    {
        $sparql = 'SELECT DISTINCT ?prod WHERE {?prod rdf:type data:Product';

        if (isset($pref['price']['start']) && isset($pref['price']['end']) && ! empty($pref['price']['start']) && ! empty($pref['price']['end'])) {
            $start = isset($pref['price']['start']) ? $pref['price']['start'] : $pref['price']['end'];
            $end = isset($pref['price']['end']) ? $pref['price']['end'] : $pref['price']['start'];

            $sparql .= '.?prod data:hasPrice ?price.FILTER (?price > "' . $start . '"^^xsd:long && ?price <= "' . $end . '"^^xsd:long) ';
        }

        if (isset($pref['os']) && ! empty($pref['os'])) {
            foreach ($pref['os'] as $os)
                $sparql .= ' {?prod data:hasOS "' . $os . '"^^xsd:string} UNION ';
            $sparql = substr($sparql, 0, -6);
        }

        if (isset($pref['brand']) && ! empty($pref['brand'])) {
            foreach ($pref['brand'] as $brand)
                $sparql .= ' {?prod data:hasBrand "' . $brand . '"^^xsd:string} UNION ';
            $sparql = substr($sparql, 0, -6);
        }

        $sparql .= '} ORDER BY ?prod';

        return $this->execute($sparql, 1);
    }

    function get_by_sr($v)
    {
        $sparql = 'SELECT ?prod WHERE {';

        if (isset($v['screen_technology']) && ! empty($v['screen_technology'])) {
            foreach ($v['screen_technology'] as $val)
                $sparql .= '{?prod data:hasScreen_Technology "' . $val . '"^^xsd:string} UNION ';

            $sparql = substr($sparql, 0, -6);
        }

        if (isset($v['screen_resolution']) && ! empty($v['screen_resolution'])) {
            foreach ($v['screen_resolution'] as $val)
                $sparql .= '{?prod data:hasScreen_Resolution "' . $val . '"^^xsd:string} UNION ';

            $sparql = substr($sparql, 0, -6);
        }

        if (isset($v['processor']) && ! empty($v['processor'])) {
            foreach ($v['processor'] as $val)
                $sparql .= '{?prod data:hasProcessor "' . $val . '"^^xsd:string} UNION ';

            $sparql = substr($sparql, 0, -6);
        }

        if (isset($v['ram']) && ! empty($v['ram'])) {
            foreach ($v['ram'] as $val)
                $sparql .= '{?prod data:hasRam "' . $val . '"^^xsd:string} UNION ';

            $sparql = substr($sparql, 0, -6);
        }

        if (isset($v['primary_camera']) && ! empty($v['primary_camera'])) {
            foreach ($v['primary_camera'] as $val)
                $sparql .= '{?prod data:hasPrimary_Camera "' . $val . '"^^xsd:string} UNION ';

            $sparql = substr($sparql, 0, -6);
        }

        if (isset($v['secondary_camera']) && ! empty($v['secondary_camera'])) {
            foreach ($v['secondary_camera'] as $val)
                $sparql .= '{?prod data:hasPrimary_Camera "' . $val . '"^^xsd:string} UNION ';

            $sparql = substr($sparql, 0, -6);
        }

        if (isset($v['internal_memmory']) && ! empty($v['internal_memmory'])) {
            foreach ($v['internal_memmory'] as $val)
                $sparql .= '{?prod data:hasMemori_Internal "' . $val . '"^^xsd:string} UNION ';

            $sparql = substr($sparql, 0, -6);
        }

        if (isset($v['video_record']) && ! empty($v['video_record'])) {
            foreach ($v['video_record'] as $val)
                $sparql .= '{?prod data:hasVideo_Record_Quality "' . $val . '"^^xsd:string} UNION ';

            $sparql = substr($sparql, 0, -6);
        }

        if (isset($v['screen_size']) && ! empty($v['screen_size'])) {
            foreach ($v['screen_size'] as $val)
                $sparql .= '{?prod data:hasScreen_Size ?size.' . $val . '} UNION ';

            $sparql = substr($sparql, 0, -6);
        }

        if (isset($v['battery_capacity']) && ! empty($v['battery_capacity'])) {
            foreach ($v['battery_capacity'] as $val)
                $sparql .= '{?prod data:hasBattery ?capacity.' . $val . '} UNION ';

            $sparql = substr($sparql, 0, -6);
        }

        $sparql .= '} ORDER BY ?prod';

        //return $sparql;
        return $this->execute($sparql, 1);
    }

    function get_type($pref)
    {
        $sparql = 'SELECT DISTINCT ?prod WHERE {?prod rdf:type data:Product ';

        if (isset($pref['type']) && ! empty($pref['type'])) {
            foreach ($pref['type'] as $type)
                $sparql .= '{?prod rdf:type data:' . $type . '} UNION ';

            $sparql = substr($sparql, 0, -6);
        }

        $sparql .= '} ORDER BY ?prod';

        return $this->execute($sparql, 1);
    }

    //=======================================================================================================================================
    // Custom Function
    //=======================================================================================================================================
    function Children($array)
    {
        $result = array();

        foreach ($array as $var) {
            $sparql = 'SELECT ?child WHERE { ?child rdf:type data:' . $var . ' }';
            $temp = $this->execute($sparql, 0);

            if (empty($temp)) {
                $sparql = 'SELECT ?child WHERE { ?child rdfs:subClassOf data:' . $var . ' }';
                $temp = $this->execute($sparql, 0);
            }

            $result = array_merge($result, $temp);
        }

        return array_unique($result);
    }

    function Parent($array)
    {
        $result = array();

        foreach ($array as $var) {
            $sparql = 'SELECT ?parent WHERE { data:' . $var . ' rdf:type ?parent  }';
            $temp = $this->execute($sparql, 0);

            if (empty($temp)) {
                $sparql = 'SELECT ?parent WHERE { data:' . $var . ' rdfs:subClassOf ?parent }';
                $temp = $this->execute($sparql, 0);
            }

            $result = array_merge($result, $temp);
        }

        return array_unique($result);
    }

    function SpecP($var)
    {
        $sparql = 'SELECT ?spec WHERE { data:' . $var . ' data:hasSpec ?spec }';

        return $this->execute($sparql, 1);
    }

    function Type($var)
    {
        $sparql = 'SELECT ?jenis WHERE { data:' . $var . ' rdf:type ?jenis }';

        return $this->execute($sparql, 1);
    }

    //-----

    function satisfy_individu($product, $fr)
    {
        $specf = $this->supp_by($fr);
        $specp = $this->hasSpec($product);

        if ($this->_is_identical($this->get_parent($specf), $this->get_parent(array_intersect($specf, $specp))))
            return true;
        else
            return false;
    }

    function get_parent($array)
    {
        $result = array();

        foreach ($array as $var) {
            $temp = $this->lower_class($var);
            if (empty($temp)) $temp = $this->lower_individu($var);

            $result = array_merge($result, $temp);
        }

        return array_unique($result);
    }

    function _is_identical($array1, $array2)
    {
        if (count(array_diff($array1, $array2)) == 0)
            return true;
        else
            return false;
    }

    function generate_product()
    {
        $prod = $this->get_individu('Product');
        $fr = $this->get_individu('FuncReq');

        foreach ($prod as $p) {
            $temp = array();
            foreach ($fr as $f) {
                if ($this->satisfy_individu($p, $f)) $temp[] = $f;
            }
            echo $p . ' --> ';
            print_r($temp);
            echo '<br>';
            $result[] = array('product' => $p, 'suppf' => $temp);
        }

        //echo '<pre>';
        //print_r($result);
        //echo '</pre>';
    }

    function get_product()
    {
        $path = realpath(APPPATH . '../prod');
        $file = $path . '/product.crs';
        $json = file_get_contents($file);

        return json_decode($json);
    }
}