import { BrowserRouter, Routes, Route, Link } from 'react-router-dom';
import 'bootstrap/dist/css/bootstrap.min.css';

import './App.css';
import CreateProduct from './components/CreateProduct';
import ListProduct from './components/ListProduct';
import UpdateProduct from './components/UpdateProduct';
import DeleteProduct from './components/DeleteProduct';

import NavBar from './components/NavBar';


function App() {
  return (
    <div className="App">
      <NavBar />

      <BrowserRouter>
        {/*
          <nav>
            <ul className='list'>
              <li>
                <Link to="/">List Products</Link>
              </li>
              <li>
                <Link to="products/add">Add Product</Link>
              </li>
            </ul>
          </nav>
          */
        }
        <Routes>
          <Route index element={<ListProduct />} />
          <Route path="products/add" element={<CreateProduct />} />
          <Route path="products/:id/delete" />
        </Routes>
      </BrowserRouter>

    </div>
  );
}

export default App;
