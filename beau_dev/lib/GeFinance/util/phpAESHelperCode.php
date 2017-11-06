<?php

// Takes 3 values - the message to be encrypted and the KEY encoded in BASE64 to be used for this message.
// Returns the encrypted message with the IV for this specific message prepended which is then encoded in BASE64.
// NOTE: The IV is MUST BE, and is UNIQUE per message
function fnEncrypt($strCleartextMessage, $strSecretKey) {
	// $GLOBAL_strCypherAlgo, $GLOBAL_strCypherMode, $GLOBAL_intCypherBlockSize;
        $GLOBAL_strCypherAlgo = MCRYPT_RIJNDAEL_128;
        $GLOBAL_strCypherMode = MCRYPT_MODE_CBC;
        $GLOBAL_intCypherBlockSize = mcrypt_get_block_size ( $GLOBAL_strCypherAlgo, $GLOBAL_strCypherMode );

    
	// convert the key into BYTE form.
	$byteSecretKey = base64_decode ( $strSecretKey );
	
	// GENERATE a random and unique IV for this message based on the ALGO and MODE used.
	$byteIV = mcrypt_create_iv ( mcrypt_get_iv_size ( $GLOBAL_strCypherAlgo, $GLOBAL_strCypherMode ) );
	
	// add appropriate padding to the cleartext based on the BLOCKSIZE used.
	$strPaddedCleartext = fnPKCS7PadMessage ( $strCleartextMessage, $GLOBAL_intCypherBlockSize );
	
	// encrypt the message
	$byteCyphertextMessage = mcrypt_encrypt ( $GLOBAL_strCypherAlgo, $byteSecretKey, $strPaddedCleartext, $GLOBAL_strCypherMode, $byteIV );
	
	// return the cypher text and the IV.
	return trim ( base64_encode ( $byteIV . $byteCyphertextMessage ) );
}

// Takes only 2 values - the message to be decrypted and the KEY to be used. Both are expected to be BASE64 encoded.
// Assumes that the IV was prepended to the cyphertext BEFORE it was converted to BASE64
function fnDecrypt($strCyphertextMessage, $strSecretKey) {
	// global $GLOBAL_strCypherAlgo, $GLOBAL_strCypherMode, $GLOBAL_intCypherBlockSize;
        $GLOBAL_strCypherAlgo = MCRYPT_RIJNDAEL_128;
        $GLOBAL_strCypherMode = MCRYPT_MODE_CBC;
        $GLOBAL_intCypherBlockSize = mcrypt_get_block_size ( $GLOBAL_strCypherAlgo, $GLOBAL_strCypherMode );

	// convert the key into BYTE form.
	$byteSecretKey = base64_decode ( $strSecretKey );
	
	// convert the combined IV and cyphertext message into BYTE form
	$byteCyphertextMessage = base64_decode ( $strCyphertextMessage );
	
	// seperate the IV and the inner cyphertext message
	$intIVLength = mcrypt_get_iv_size ( $GLOBAL_strCypherAlgo, $GLOBAL_strCypherMode );
	$byteIV = substr ( $byteCyphertextMessage, 0, $intIVLength );
	$byteInnerCyphertextMessage = substr ( $byteCyphertextMessage, $intIVLength );
	
	// DEBUG CODE ONLY
	// echo "IV = ".base64_encode($byteIV).".\n";
	// echo "CYPHER = ".base64_encode($byteInnerCyphertextMessage).".\n";
	
	// Decrypt message
	$strPaddedCleartextMessage = mcrypt_decrypt ( $GLOBAL_strCypherAlgo, $byteSecretKey, $byteInnerCyphertextMessage, $GLOBAL_strCypherMode, $byteIV );
	
	// return the unpadded cleartext
	return trim ( fnPKCS7UnpadMessage ( $strPaddedCleartextMessage ) );
}

// Helper function to simulate the PCK7Padding
function fnPKCS7PadMessage($strSource, $intBlockSize) {
	$intLen = strlen ( $strSource );
	$intPadValue = $intBlockSize - ($intLen % $intBlockSize);
	$strSource .= str_repeat ( chr ( $intPadValue ), $intPadValue );
	
	return $strSource;
}

// Helper function to simulate the unpadding of PCK7Padding
function fnPKCS7UnpadMessage($strSource) {
	$intPaddingAmount = ord ( $strSource {strlen ( $strSource ) - 1} );
	if ($intPaddingAmount > strlen ( $strSource ))
		return false;
	if (strspn ( $strSource, chr ( $intPaddingAmount ), strlen ( $strSource ) - $intPaddingAmount ) != $intPaddingAmount)
		return false;
	
	return substr ( $strSource, 0, - 1 * $intPaddingAmount );
}

// Helper function to read the key file and read the content of the key file and then close the key file.
function fnReadFile($myFile) {
	$fh = fopen ( $myFile, 'r' );
	$theData = fread ( $fh, filesize ( $myFile ) );
	fclose ( $fh );
	return $theData;
}

