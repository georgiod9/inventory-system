import Card from "react-bootstrap/Card";
import Form from "react-bootstrap/Form";
import Container from "react-bootstrap/Container";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import { useState } from "react";

import { useEffect } from "react";

export default function ItemCard(props) {
    const [products, setProducts] = useState([]);
    useEffect(() => {
        console.log("Received from parent: ", props['products']);
        props['products'] ? setProducts(props['products']) : console.log("waiting to fetch products from server...");
        
        if(products.length) {console.log("Final product:", products) }
    })

    return (
        <>
            {products.length && products.map((item) => (
                
                    <Card style={{ width: '12rem', height: '10rem' }}>
                        <Card.Header>
                            <Container>
                                <Row>
                                    <Col xs={1}>
                                        <Form.Check />
                                    </Col>

                                    <Col xs={9}>
                                        {item.sku}
                                    </Col>

                                </Row>
                            </Container>
                        </Card.Header>


                        <Card.Body>
                            <Card.Title>{item.name}</Card.Title>
                            <Card.Text>{item.price} $</Card.Text>
                            <Card.Text>details</Card.Text>
                        </Card.Body>
                    </Card>
                
            ))}

        </>


    );
}