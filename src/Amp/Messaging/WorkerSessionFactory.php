<?php

namespace Amp\Messaging;

class WorkerSessionFactory {
    
    private $workerFactory;
    
    function __construct(WorkerFactory $wf = NULL) {
        $this->workerFactory = $wf ?: new WorkerFactory;
    }
    
    function __invoke($cmd, $cwd = NULL) {
        $worker = $this->workerFactory->__invoke($cmd, $cwd);
        
        list($writePipe, $readPipe, $errorPipe) = $worker->getPipes();
        
        $parser = new FrameParser($readPipe);
        $writer = new FrameWriter($writePipe);
        
        return new WorkerSession($worker, $parser, $writer);
    }
    
}

