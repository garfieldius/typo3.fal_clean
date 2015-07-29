<?php
namespace GeorgGrossberger\FalClean\Output;
/*                                                                       *
 * Copyright 2014 Georg Großberger <contact@grossberger-ge.org>          *
 *                                                                       *
 * This is free software; you can redistribute it and/or modify it under *
 * the terms of the MIT- / X11 - License                                 *
 *                                                                       */

interface OutputInterface {

	public function info($message, array $arguments = array());

	public function error($message, array $arguments = array());
}
