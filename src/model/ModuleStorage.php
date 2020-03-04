<?php

interface ModuleStorage{
	public function read($id);
	public function readAll();
	public function create(Module $m);
	public function delete($id);
}

