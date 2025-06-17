<?php

if (!function_exists('save_image')) {
    /**
     * Save an uploaded image to the writable directory
     * 
     * @param array|SplFileInfo $file The uploaded file from $_FILES or SplFileInfo object
     * @return string|false Returns the saved file path on success, false on failure
     */
    function save_image($file)
    {
        // Handle SplFileInfo object
        if ($file instanceof \SplFileInfo) {
            $tmpName = $file->getPathname();
            $name = $file->getFilename();
        } 
        // Handle array from $_FILES
        else if (is_array($file)) {
            if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
                return false;
            }
            $tmpName = $file['tmp_name'];
            $name = $file['name'];
        } else {
            return false;
        }

        // Create uploads directory if it doesn't exist
        $uploadPath = WRITEPATH . 'uploads';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Generate a unique filename
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        
        // Move the uploaded file
        if (move_uploaded_file($tmpName, $uploadPath . '/' . $filename)) {
            return $filename;
        }
        
        return false;
    }
}

if (!function_exists('delete_image')) {
    /**
     * Delete an image file from the writable directory
     * 
     * @param string $path The relative path to the image file
     * @return bool Returns true on success, false on failure
     */
    function delete_image($path)
    {
        $fullPath = WRITEPATH . $path;
        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }
} 