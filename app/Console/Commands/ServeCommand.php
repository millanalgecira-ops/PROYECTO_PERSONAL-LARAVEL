<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand as BaseServeCommand;
use Symfony\Component\Process\Process;

class ServeCommand extends BaseServeCommand
{
    protected $signature = 'serve {--host=127.0.0.1} {--port=8000} {--tries=10} {--no-reload}';
    
    public function handle()
    {
        // Verificar configuración inicial
        $this->checkSetup();
        
        // Iniciar Vite en segundo plano
        $this->startVite();
        
        // Ejecutar el comando serve original
        return parent::handle();
    }
    
    protected function checkSetup()
    {
        $setupNeeded = false;
        
        // Verificar .env
        if (!file_exists(base_path('.env'))) {
            $this->info('⚠ Archivo .env no encontrado. Creando desde .env.example...');
            copy(base_path('.env.example'), base_path('.env'));
            $setupNeeded = true;
        }
        
        // Verificar vendor
        if (!is_dir(base_path('vendor'))) {
            $this->warn('⚠ Dependencias de PHP no instaladas.');
            if ($this->confirm('¿Deseas instalar las dependencias de composer ahora?', true)) {
                $this->info('Instalando dependencias de PHP...');
                $this->runProcess(['composer', 'install', '--no-interaction']);
                $this->info('✓ Dependencias de PHP instaladas');
            }
        }
        
        // Verificar APP_KEY
        $envContent = file_get_contents(base_path('.env'));
        if (!preg_match('/APP_KEY=base64:/', $envContent)) {
            $this->info('Generando clave de aplicación...');
            $this->call('key:generate');
            $setupNeeded = true;
        }
        
        // Verificar node_modules
        if (!is_dir(base_path('node_modules'))) {
            $this->warn('⚠ Dependencias de Node.js no instaladas.');
            if ($this->confirm('¿Deseas instalar las dependencias de npm ahora?', true)) {
                $this->info('Instalando dependencias de Node.js...');
                $this->runProcess(['npm', 'install']);
                $this->info('✓ Dependencias de Node.js instaladas');
            }
        }
        
        if ($setupNeeded) {
            $this->newLine();
            $this->info('✓ Configuración inicial completada');
            $this->newLine();
        }
    }
    
    protected function startVite()
    {
        if (!is_dir(base_path('node_modules'))) {
            return;
        }
        
        $this->info('🚀 Iniciando Vite...');
        
        // Iniciar Vite en segundo plano usando start en Windows
        if (PHP_OS_FAMILY === 'Windows') {
            pclose(popen('start /B npm run dev', 'r'));
        } else {
            pclose(popen('npm run dev > /dev/null 2>&1 &', 'r'));
        }
        
        sleep(2); // Esperar un poco para que Vite inicie
        
        $this->info('✓ Vite iniciado en http://localhost:5173');
        $this->newLine();
    }
    
    private function runProcess(array $command)
    {
        $process = new Process($command);
        $process->setTimeout(null);
        $process->setWorkingDirectory(base_path());
        $process->run(function ($type, $buffer) {
            $this->line($buffer);
        });
    }
}
