<?php

class SFotoModelBehavior extends CActiveRecordBehavior
{
    public $attributes = array('_foto_files_temp');
		public $file_array_name = 'ffile';
		
		public $path_template = "";

		public function afterSave() {
			/* пребираем все атрибуты модели в которых сохранены данные о фото файлах */
			
			$this->owner->fotos = $this->owner->fotos; 
			
			foreach ($this->attributes as $attr) {
					/* если атрибут имеет данные ... */
					if (!empty($this->owner->$attr)) {
									/* данные атрибута */
									$attr_data = $this->owner->$attr;
									
						foreach($attr_data[$file_array_name] as $key=>$name) {
								$foto = new Foto;
								
								$foto->_temp_file_name = $name; // имя временного файла (из формы)
								
								// путь сохранения
								$foto->_save_to = $this->SavePath;
								
								// все фото имеют данные о инва+
								$foto->inva = '';
								if ($attr_data['inva'][$key]) $foto->inva = $attr_data['inva_block'][$key];
								
								$foto->create_time = date('YYYY-mm-dd HH:ii:ss');
								
								if ($foto->save()) {
									array_push($this->owner->fotos, $foto);
								}
						}
					}
			}
		}
		
		public function getSavePath() {
			return strtolower(get_class($this->owner)).$this->owner->id;
    }
}
