<?php

namespace frontend\services;

use common\models\Letter;
use frontend\components\Constant;
use frontend\repositories\FileRepository;
use frontend\repositories\LetterRepository;
use Yii;
use yii\helpers\Url;
use yii\web\UploadedFile;

class LetterService
{
    public function __construct(protected LetterRepository $repository, protected FileRepository $fileRepository = new FileRepository())
    {

    }


    public function createLetter(array $data): Letter
    {
        $letter = $this->repository->create($data);
        $this->repository->updateSuggestion($letter);

        if (UploadedFile::getInstancesByName(Constant::LETTER)) {
            $this->fileRepository->saveFiles(reference: $letter->id, tabName: Constant::LETTER, folderId: null);
        }

        if(!empty($data[$letter->formName()]['notification_receiver'])){
            $notificationData = [
                'title' => 'LETTER: ' .  $letter->entity,
                'link' =>  Url::base(scheme: true) . '/letter/view?uuid=' . $letter->uuid,
                'text' => $letter->description
            ];
            $notificationService = new NotificationService();
            $notificationService->createNotification($notificationData, $data[$letter->formName()]['notification_receiver']);
        }
   
        return $letter;
    }

    public function updateLetter(Letter $model, array $data): Letter
    {
        $letter = $this->repository->update($model, $data);
        $this->repository->updateSuggestion($letter);

        return $letter;
    }
}

