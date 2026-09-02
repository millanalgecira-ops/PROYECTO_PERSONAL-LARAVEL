<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ServeAll extends Command
{
    protected $signature = 'serve:all {--port=8000}';
    protected $description = 'Inicia el servidor Laravel y Vite simultáneamente';

    public function handle()
    {
        $this->info('=========================================');
        $this->info('  Iniciando La Parrilla - Sistema');
        $this->info('=========================================');
        $this->newLine();

        // Verificar .env
        if (!file_exists(base_path('.env'))) {
            $this->info('[1/6] Creando archivo .env...');
            copy(base_path('.env.example'), base_path('.env'));
            $this->info('✓ Archivo .env creado');
        } else {
            $this->info('[1/6] Archivo .env ya existe');
        }

        // Verificar vendor
        if (!is_dir(base_path('vendor'))) {
            $this->info('[2/6] Instalando dependencias de PHP...');
            $this->runProcess(['composer', 'install', '--no-interaction']);
            $this->info('✓ Dependencias de PHP instaladas');
        } else {
            $this->info('[2/6] Dependencias de PHP ya instaladas');
        }

        // Verificar APP_KEY
        if (empty(env('APP_KEY'))) {
            $this->info('[3/6] Generando clave de aplicación...');
            $this->call('key:generate');
            $this->info('✓ Clave generada');
        } else {
            $this->info('[3/6] Clave de aplicación ya configurada');
        }

        // Verificar node_modules
        if (!is_dir(base_path('node_modules'))) {
            $this->info('[4/6] Instalando dependencias de Node.js...');
            $this->runProcess(['npm', 'install']);
            $this->info('✓ Dependencias de Node.js instaladas');
        } else {
            $this->info('[4/6] Dependencias de Node.js ya instaladas');
        }

        // Migraciones opcionales
        $this->info('[5/6] Verificando base de datos...');
        if ($this->confirm('¿Deseas ejecutar las migraciones?', false)) {
            $this->call('migrate', ['--force' => true]);
            $this->info('✓ Migraciones ejecutadas');
        } else {
            $this->info('- Migraciones omitidas');
        }

        $this->info('[6/6] Iniciando servidores...');
        $this->newLine();

        $port = $this->option('port');

        $this->info('=========================================');
        $this->info('  Servidores iniciados correctamente');
        $this->info('=========================================');
        $this->info("  Laravel: http://localhost:{$port}");
        $this->info('  Vite: http://localhost:5173');
        $this->info('=========================================');
        $this->newLine();
        $this->info('Presiona Ctrl+C para detener los servidores');
        $this->newLine();

        // Iniciar ambos procesos
        $laravelProcess = new Process(['php', 'artisan', 'serve', "--port={$port}"]);
        $laravelProcess->setTimeout(null);
        $laravelProcess->setWorkingDirectory(base_path());

        $viteProcess = new Process(['npm', 'run', 'dev']);
        $viteProcess->setTimeout(null);
        $viteProcess->setWorkingDirectory(base_path());

        // Iniciar procesos
        $laravelProcess->start();
        $viteProcess->start();

        // Mantener vivos los procesos
        while ($laravelProcess->isRunning() || $viteProcess->isRunning()) {
            usleep(100000); // 0.1 segundos
            
            // Mostrar output de Laravel
            if ($laravelOutput = $laravelProcess->getIncrementalOutput()) {
                $this->line($laravelOutput);
            }
            
            // Mostrar output de Vite
            if ($viteOutput = $viteProcess->getIncrementalOutput()) {
                $this->line($viteOutput);
            }
        }

        return 0;
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
