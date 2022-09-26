import Card from "react-bootstrap/Card";
import Form from "react-bootstrap/Form";
import Container from "react-bootstrap/Container";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import { useState } from "react";
import './Grid.css';

import { useEffect } from "react";

export default function ItemCard(props) {
    const [products, setProducts] = useState([]);
    //console.log("STARTED REACT")
    useEffect(() => {
        // console.log("Received from parent: ", props['products']);
        props['products'] ? setProducts(props['products']) : console.log("waiting to fetch products from server...");

        if (products.length) { console.log("Final product:", products) }
    })

    const fetchAttributes = (item) => {
            console.log(item)
        
        return (

            <div>
                {item.map((single_attribute) => (
                     <Card.Text>
                        {single_attribute}
                     </Card.Text>
                ))}
            </div>
           
            
        )
    }

    return (
        <div className="grid">
            {products.length && products.map((item) => (

                <Card className="card-box" style={{ margin: '20px', width: '12rem', height: '13rem' }}>
                    <Card.Header>
                        <Container style={{  }}>
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


                    <Card.Body >
                        <div className="center-text">
                        <Card.Title>{item.name}</Card.Title>
                        <Card.Text>{item.price} $</Card.Text>

                        {
                            fetchAttributes(item.attr)   
                        }
                        </div>
                        

                    </Card.Body>
                </Card>

            ))}

        </div>


    );
}