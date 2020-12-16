<?php
/**
 * download-csv.php
 *
 * @link       https://www.linkedin.com/in/alessandro-giommi-64814614b/
 * @since      1.0.0
 * @author     Gero Nikolov (@gerthrudy)
 * @date       11/11/2020 11:57
 *
 *
 * @Last       modified by:   Gero Nikolov (@gerthrudy)
 * @Last       modified time: 11/11/2020 11:57
 *
 * @package    crowdappquality
 *
 * @param $cp_id
 * @param $bug_id
 *
 * @return array
 */

function appq_csv_exporter_upload_bugs( $cp_id, $bug_id ) {
    $csv_api = new CSVRestApi( $cp_id );
    return $csv_api->csv_bug_upload( $bug_id );
}