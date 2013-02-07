<?php

/**
 * Finish uploaded files previews manipulations.
 */
class MmfileGeneratePreviewCommand extends CConsoleCommand
{
    public function run($args)
    {
        $models = Mmfile::model()->findAllByAttributes(array('preview' => false));

        foreach ($models as $m) {
            // Create previews by file extension.
            $fileExtension = strtolower(pathinfo($m->name, PATHINFO_EXTENSION));
            switch ($fileExtension) {
                case 'doc':
                case 'docx':
                case 'odt':
                case 'ppt':
                case 'xls':
                case 'xlsx':
                    Mmfile::createDocPreview($m->getUploadPath('name'));
                    break;
                case 'pdf':
                    Mmfile::createPdfPreview($m->getUploadPath('name'));
                    break;
                case 'jpeg':
                case 'jpg':
                case 'gif':
                case 'png':
                    Mmfile::createImgPreview($m->getUploadPath('name'));
                    break;
            }

            // Set file preview status.
            $m->preview = true;
            $m->save();
        }
    }
}
