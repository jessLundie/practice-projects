<?php

function label_but_errors_not_handled(int $value) {
	return [1 => 'low', 2 => 'med', 3 => 'high'][$value];
}

function label_but_errors_thrown($value) {
	if (!is_int($value) || $value < 1 || $value > 3) {
		throw new InvalidArgumentException('need a value between 1 and 3: received ' . $value);
	}

	return [1 => 'low', 2 => 'med', 3 => 'high'][$value];
}

function label_but_errors_described($value) {
	if (!is_int($value)) {
        return [false, 'need an integer between 1 and 3: received ' . var_export($value, true)];
    }
    
    if ($value < 1 || $value > 3) {
        return [false, 'need a value between 1 and 3: received ' . $value];
	}

	$result = [1 => 'low', 2 => 'med', 3 => 'high'][$value];
    return [true, $result];
}

list($got_label, $result) = label_but_errors_described(5);
if (!$got_label) {
    send_error_message($result);
    return
}

$label = $result;

////

case getlabelmaybe(5) of
    Some(Label) -> assign_label(Label)
    Nothing     -> print "could not get label"

case getLabel(5) of
    Ok(Label)          -> assign_label(Label)
    Error(Explanation) -> print(Explanation)
