<?php
/**
 * qdPM
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@qdPM.net so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade qdPM to newer
 * versions in the future. If you wish to customize qdPM for your
 * needs please refer to http://www.qdPM.net for more information.
 *
 * @copyright  Copyright (c) 2009  Sergey Kharchishin and Kym Romanets (http://www.qdpm.net)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
?>
<h3 class="page-title"><?php echo __('Import Spreadsheet (Step 2)') ?></h3>

<div><?php echo __('Bind Task fields with spreadsheet columns below and start import.') ?><br><?php echo __('Column without binded field will be not imported.') ?></div><br>

<form id="import" action="<?php echo url_for('tools/xlsTasksImport') ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="sf_method" value="put" />
    <?php echo input_hidden_tag('projects_id', $sf_request->getParameter('projects_id')) . input_hidden_tag('import_file', $import_file); ?>



    <div class="table-scrollable">
        <table class="table table-striped table-bordered table-hover">
            <?php
            $objWorksheet = $objPHPExcel->getActiveSheet();

            $highestRow = $objWorksheet->getHighestRow(); // e.g. 10
            $highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'

            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
            
            echo '<tr>';
            for ($j = 1; $j <= $highestColumnIndex; $j++)
            {
                echo '<th><a style="font-weight: bold;" href="javascript: openModalBox(\'' . url_for('tools/xlsTasksImportBindField?col=' . $j) . '\')">' . __('Bind Field') . '</a><div id="column_' . $j . '">-</div></ht>';
            }
            echo '</tr>';

            for ($i = 1; $i <= $highestRow; $i++)
            {
                echo '<tr>';
                for ($j = 1; $j <= $highestColumnIndex; $j++)
                {
                    $value = strip_tags(trim($objWorksheet->getCellByColumnAndRow($j, $i)->getValue())); 
                    echo '<td>' . $value . '</td>';
                }
                echo '</tr>';

                if ($i == 10)
                {
                    break;
                }
            }
            ?>
        </table>  
    </div>
    <br>

    <input id="import_first_row" name="import_first_row" type="checkbox" value="1"> <label for="import_first_row"><?php echo __('Import first row') ?></label><br>
    <br>  
<?php echo submit_tag(__('Import')) ?>
</form>

<script>
    function bindField(col)
    {
        var field_name = '-';
        $.post("<?php echo url_for('tools/xlsTasksImportBindField') ?>", $("#bind_filed").serialize()).success(function (data) {
            if (data != '-')
            {
                $('#column_' + col).html('<div class="background_color_item" style="background: #56C71E; color: white;">' + data + '</div>');
            } else
            {
                $('#column_' + col).html('-');
            }
            jQuery.fn.modal('hide');

            $('#ajax-modal').modal('hide')
        });
    }
    </script
