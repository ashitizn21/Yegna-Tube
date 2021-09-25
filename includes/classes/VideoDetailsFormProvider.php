<?php
    // require_once("../config.php");
    class VidoeDetailsFormProvider {
        private $con;

        public function __construct($con)
        {
            $this->con = $con;
        }
        public function createUploadForm(){

            $fileInput = $this->createFileInpute();
            $titleInput = $this->createTitleInput();
            $descriptionInput = $this->createDescriptionInput();
            $categoryInput = $this->createCategoryInput();
            $privacyInput = $this->createPrivacyInput();
            $uploadBtn = $this->createUploadButton();
            return "<form action='processing.php' method='POST' class='p-4'>
                        $fileInput
                        $titleInput
                        $descriptionInput
                        $categoryInput
                        $privacyInput
                        $uploadBtn
                    </form>";
        }

        private function createFileInpute(){
            
        return   " <div class='form-group mt-2'>
                    <label for='exampleFormControlFile1'>Your File</label>
                    <input type='file' class='form-control-file' name='fileInput' id='exampleFormControlFile1' required>
                </div>";
        }

        private function createTitleInput() {
            return "<div class='form-group mt-2'>
                        <input class='form-control' type='text' name='titleInput' placeholder='title' required>
                    </div>";
        }

        private function createDescriptionInput() {
            return "<div class='form-group mt-2'>
                        <textarea class='form-control'  name='descriptionInput' placeholder='Description'  rows='3' > </textarea>
                    </div>";
        }

        private function createCategoryInput() {

            $html = "<div class='form-group mt-2' >
                        <select class='form-control' name='categoryInput'>";

            $query = $this->con->prepare("SELECT * FROM categories");
            $query->execute();
            
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $id = $row['id'];
                $html .= "<option value='$id'>".$row['name']."</option>"."<br>";
            }
            $html .= "  </select>
                     </div>";
            
             return $html;
            
        }

        private function createPrivacyInput() {
            return "<div class='form-group mt-2'>
                        <select class='form-control' name='privacyInput'>
                            <option value='0'>Private</option>
                            <option value='1'>Public</option>
                        </select>
                        </div>";
        }

        private function createUploadButton(){
            return "<button type='submit' name='uploadButton' class='btn btn-primary m-2'>Upload
                    </button>";
        }
    }


?>