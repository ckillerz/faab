function a( time ) { 
	m = document.getElementById( 'message' );
	if( m ) {
		m.focus(); 
		m.value = m.value + time + ' ' ; 
	}
}

function s( id ) {
	m = document.getElementById( "i" + id );
	if( m ) m.style.background = '#dde6e6' ;
}

function h( id ) {
	m = document.getElementById( "i" + id );
	if( m ) m.style.background = 'white' ;
}

