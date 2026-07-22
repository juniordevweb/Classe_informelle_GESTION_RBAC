<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('force_password_reset')) {
            $path = $this->normalizePath($request->getUri()->getPath());
            $allowedPaths = [
                'password/reset',
                'logout',
            ];

            if (! in_array($path, $allowedPaths, true)) {
                return redirect()->to('/password/reset');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }

    private function normalizePath(string $path): string
    {
        $path = trim($path, '/');

        if (str_starts_with($path, 'index.php/')) {
            $path = substr($path, strlen('index.php/'));
        }

        return trim($path, '/');
    }
}
