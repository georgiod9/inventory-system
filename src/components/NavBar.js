import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Nav from 'react-bootstrap/Nav';
import Navbar from 'react-bootstrap/Navbar';
import NavDropdown from 'react-bootstrap/NavDropdown';
import Button from 'react-bootstrap/Button';
import '../../src/App.css';

function NavBar() {
    return (
        <Navbar bg="light" expand="lg">
            <Container >

                <Navbar.Brand href="/">ScandiWeb Market</Navbar.Brand>
                <Navbar.Toggle aria-controls="basic-navbar-nav" />
                <Navbar.Collapse id="basic-navbar-nav">
                    <Nav className="me-auto">
                        <Nav.Link href="/products/add">Add Product</Nav.Link>
                        <Nav.Link href="/">Product List</Nav.Link>

                    </Nav>
                </Navbar.Collapse>

                <Button variant="primary" className="me-2">Add</Button>{' '}
                
                <Button variant="outline-danger" >Mass Delete</Button>{' '}

            </Container>

        </Navbar>
    );
}

export default NavBar;