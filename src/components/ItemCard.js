import Card from "react-bootstrap/Card";
import Form from "react-bootstrap/Form";
import Container from "react-bootstrap/Container";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import { useState } from "react";
import { useRef } from "react";
import './Grid.css';

import { ReactDOM } from "react";

import { useEffect } from "react";
import { FormGroup } from "react-bootstrap";

export default function ItemCard(props) {
    const [products, setProducts] = useState([]);
    const [isSelected, setSelected] = useState(false);
   
    var [selection, setSelection] = useState([]);
    
    var list = [];

    //console.log("STARTED REACT")
    useEffect(() => {
        //console.log("Received from parent: ", props['products']);
        console.log("props: ", props['selection'][0])
        selection = props['selection'][0];
        setSelection = props['selection'][1];
        props['products'] ? setProducts(props['products']) : console.log("waiting to fetch products from server...");
       
        if (products.length) { console.log("Final product:", products) }
       
    })

    const fetchAttributes = (item) => {
        //console.log(item)

        //if there are more than 1 attribute in the array, 
        //map the content to its own Card.Text field
        return (
            <div>
                {item && item.map((single_attribute) => (
                    <Card.Text>
                        {single_attribute}
                    </Card.Text>
                ))}
            </div>
        )
    }


    const handleChange = (event) => {
        console.log("Presed: ",event.target.id)
        if(!isSelected) {
            setSelected(true);
           
            selection.push(event.target.id)
            console.log("Selection: ", selection)

        } else {
            setSelected(false);
            selection.forEach(element => {
                console.log("element: " + element +" id: " +event.target.id)
                if(element != event.target.id) {
                    list.push(element);
                }

            });
            console.log("new list:", list)
            setSelection(list);
            console.log("reformattedto: ",selection)

        }
    }

    return (
        <div >
            <Card className="card-box" style={{ margin: '20px', width: '15rem', height: '13rem' }}>
                <Card.Header>
                    <Container style={{}}>
                        <Row>
                            <Col xs={1}>
                                <FormGroup onClick={handleChange} >
                                    <Form.Check
                                        id={products.id}>
                                    </Form.Check>
                                </FormGroup>

                            </Col>

                            <Col xs={9}>
                                {products.sku}
                            </Col>

                        </Row>
                    </Container>
                </Card.Header>


                <Card.Body >
                    <div className="center-text">
                        <Card.Title>{products.name}</Card.Title>
                        <Card.Text>{products.price} $</Card.Text>
                        {
                            fetchAttributes(products.attr)
                        }
                    </div>


                </Card.Body>
            </Card>
        </div>

    );
}