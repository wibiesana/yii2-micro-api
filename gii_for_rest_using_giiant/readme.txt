This repository does not use Gii by default.
To generate CRUD code using Giiant:

Use a fresh Yii2 app that has Gii installed.

Replace the files in giiant/src/generators/crud/default/controller-rest with the file provided.
Replace the files in giiant/src/generators/model/model-extended/ with the file provided.

Run Gii to generate the CRUD code.

Copy the generated controller from controllers/api/ and paste it into this project.