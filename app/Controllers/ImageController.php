<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ImageModel;
use CodeIgniter\HTTP\ResponseInterface;

class ImageController extends BaseController
{
    protected $imageModel;

    public function __construct()
    {
        $this->imageModel = new ImageModel();
        helper('image');
    }

    public function index()
    {
        $images = $this->imageModel->findAll();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $images
        ]);
    }

    public function show($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Image ID is required'
            ])->setStatusCode(400);
        }

        $image = $this->imageModel->find($id);
        
        if (!$image) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Image not found'
            ])->setStatusCode(404);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $image
        ]);
    }

    public function create()
    {
        $file = $this->request->getFile('image');
        
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No valid image file uploaded'
            ])->setStatusCode(400);
        }

        // Save the image file
        $imagePath = save_image($file->getFileInfo());
        
        if (!$imagePath) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to save image file'
            ])->setStatusCode(500);
        }

        // Save to database
        $data = [
            'name' => $file->getClientName(),
            'image_path' => $imagePath
        ];

        if (!$this->imageModel->validate($data)) {
            // Delete the uploaded file if validation fails
            delete_image($imagePath);
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $this->imageModel->errors()
            ])->setStatusCode(400);
        }

        $imageId = $this->imageModel->insert($data);

        if (!$imageId) {
            // Delete the uploaded file if database insert fails
            delete_image($imagePath);
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to create image record'
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Image created successfully',
            'data' => [
                'id' => $imageId,
                'name' => $data['name'],
                'image_path' => $data['image_path']
            ]
        ])->setStatusCode(201);
    }

    public function update($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Image ID is required'
            ])->setStatusCode(400);
        }

        $image = $this->imageModel->find($id);
        if (!$image) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Image not found'
            ])->setStatusCode(404);
        }

        $file = $this->request->getFile('image');
        $data = [];

        if ($file && $file->isValid()) {
            // Save new image
            $imagePath = save_image($file->getFileInfo());
            
            if (!$imagePath) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to save new image file'
                ])->setStatusCode(500);
            }

            // Delete old image
            delete_image($image['image_path']);
            
            $data = [
                'name' => $file->getClientName(),
                'image_path' => $imagePath
            ];
        } else {
            // If no new file, just update the name
            $data = [
                'name' => $this->request->getPost('name') ?? $image['name']
            ];
        }

        if (!$this->imageModel->validate($data)) {
            if (isset($imagePath)) {
                delete_image($imagePath);
            }
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $this->imageModel->errors()
            ])->setStatusCode(400);
        }

        if (!$this->imageModel->update($id, $data)) {
            if (isset($imagePath)) {
                delete_image($imagePath);
            }
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to update image'
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Image updated successfully'
        ]);
    }

    public function delete($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Image ID is required'
            ])->setStatusCode(400);
        }

        $image = $this->imageModel->find($id);
        if (!$image) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Image not found'
            ])->setStatusCode(404);
        }

        // Delete the file first
        if (!delete_image($image['image_path'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to delete image file'
            ])->setStatusCode(500);
        }

        // Then delete from database
        if (!$this->imageModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to delete image record'
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Image deleted successfully'
        ]);
    }

    public function serve($filename)
    {
        $fullPath = WRITEPATH . 'uploads/' . $filename;
        
        if (!file_exists($fullPath) || !is_file($fullPath)) {
            return $this->response->setStatusCode(404);
        }

        $mime = mime_content_type($fullPath);
        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setBody(file_get_contents($fullPath));
    }
}
