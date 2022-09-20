import Card from "react-bootstrap/Card";
import Form from "react-bootstrap/Form";
import Container from "react-bootstrap/Container";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";

export default function ListProduct() {
    return (
        <Form>
            <Card style={{ width: '12rem', height: '10rem' }}>
                <Card.Header>
                    <Container>
                        <Row>
                            <Col xs={1}>
                                <Form.Check />
                            </Col>

                            <Col xs={9}>
                                DV112
                            </Col>

                        </Row>
                    </Container>
                </Card.Header>


                <Card.Body>
                    <Card.Title>DVD Name</Card.Title>
                    <Card.Text>Price: 10$</Card.Text>
                    <Card.Text>Size: 1000MB</Card.Text>

                </Card.Body>
            </Card>
        </Form>

    );
}