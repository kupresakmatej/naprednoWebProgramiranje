const express = require('express');
const session = require('express-session');
const path = require('path');
const bcrypt = require('bcryptjs');
const mysql = require('mysql');

const app = express();

const db = mysql.createConnection({
    user: 'root',
    host: 'localhost',
    password: 'yourpassword',
    database: 'GameshopDatabase'
});

// parsiranje json i encoded data za urlove
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

app.use(session({
    secret: 'kupresakmatej', //secret key - treba bit bolji, ok za testiranje
    resave: false,
    saveUninitialized: false,
    cookie: { secure: false }
}));

//pokrene index.html po defaultu
app.use(express.static(path.join(__dirname, 'public')));

app.get('/insertGames', (req, res) => {
    const games = [
        { gameName: "The Legend of Zelda: Breath of the Wild", gamePrice: 59.99, gameSplashImage: "/images/zeldaArt.jpeg" },
        { gameName: "Red Dead Redemption 2", gamePrice: 39.99, gameSplashImage: "/images/rdr2Splash.jpeg" },
        { gameName: "Cyberpunk 2077", gamePrice: 29.99, gameSplashImage: "/images/cbp77Art.jpeg" },
        { gameName: "God of War", gamePrice: 19.99, gameSplashImage: "/images/gowArt.jpeg" },
        { gameName: "Minecraft", gamePrice: 26.95, gameSplashImage: "/images/minecraftArt.jpeg" },
        { gameName: "Horizon Zero Dawn", gamePrice: 14.99, gameSplashImage: "/images/horizonArt.gif" },
        { gameName: "The Witcher 3: Wild Hunt", gamePrice: 25.00, gameSplashImage: "/images/splashArt.jpeg" },
        { gameName: "Among Us", gamePrice: 4.99, gameSplashImage: "/images/amongArt.jpeg" },
        { gameName: "Doom Eternal", gamePrice: 29.99, gameSplashImage: "/images/doomArt.jpeg" },
        { gameName: "Animal Crossing: New Horizons", gamePrice: 59.99, gameSplashImage: "/images/animalCrossingArt.jpeg" }
    ];

    let query = 'INSERT INTO games (gameName, gamePrice, gameSplashImage) VALUES ';
    const values = games.map(game => `("${game.gameName}", ${game.gamePrice}, "${game.gameSplashImage}")`).join(', ');
    query += values;

    db.query(query, (error, result) => {
        if (error) {
            console.log(error);
            res.status(500).send('Error inserting games');
        } else {
            res.send('Games inserted successfully');
        }
    });
});

app.get('/fetchGames', (req, res) => {
    db.query('SELECT * FROM games', (error, result) => {
        if (error) {
            console.log(error);
        }

        res.send(result);
    })
})

app.get('/fetchUsers', (req, res) => {
    db.query('SELECT * FROM users', (error, result) => {
        if (error) {
            console.log(error);
            res.status(500).send('Error fetching users');
        } else {
            res.send(result);
        }
    })
})

app.get('/clear', (req, res) => {
    db.query('DELETE FROM games', (error, result) => {
        if (error) {
            console.log(error);
        }

        res.send(result);
    })
})

app.get('/games', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'games.html'));
});

app.get('/cart', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'cart.html'));
})

app.get('/dashboard', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'dashboard.html'));
})

app.get('/checkout', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'checkout.html'));
})

app.post('/register', async (req, res) => {
    const { name, surname, email, username, password, isAdmin } = req.body;

    if (!name || !surname || !email || !username || !password) {
        return res.status(400).send('All fields are required');
    }

    if (!email.includes('@')) {
        return res.status(400).send('Invalid email address');
    }

    if (password.length < 8) {
        return res.status(400).send('Password must be at least 8 characters long');
    }

    try {
        db.query('SELECT * FROM users WHERE email = ? OR username = ?', [email, username], async (err, results) => {
            if (err) {
                console.error(err);
                return res.status(500).send('Server error');
            }

            if (results.find(user => user.email === email)) {
                return res.status(400).send('Email already registered');
            }

            if (results.find(user => user.username === username)) {
                return res.status(400).send('Username already taken');
            }

            const hashedPassword = await bcrypt.hash(password, 10);

            const sql = 'INSERT INTO users (name, surname, email, username, password, isAdmin) VALUES (?, ?, ?, ?, ?, ?)';
            db.query(sql, [name, surname, email, username, hashedPassword, isAdmin || false], (err, result) => {
                if (err) {
                    console.error(err);
                    return res.status(500).send('Server error');
                }

                req.session.user = {
                    id: result.insertId,
                    username: username,
                    isAdmin: isAdmin || false
                };

                res.status(200).send('Registration successful');
            });
        });
    } catch (err) {
        console.error(err);
        res.status(500).send('Server error');
    }
});

app.post('/login', (req, res) => {
    const { username, password } = req.body;

    if (!username || !password) {
        return res.status(400).send('All fields are required');
    }

    const sql = 'SELECT * FROM users WHERE username = ?';
    db.query(sql, [username], async (err, results) => {
        if (err) {
            console.error(err);
            return res.status(500).send('Server error');
        }

        if (results.length === 0) {
            return res.status(401).send('Invalid username or password');
        }

        const user = results[0];

        try {
            const isMatch = await bcrypt.compare(password, user.password);

            if (!isMatch) {
                return res.status(401).send('Invalid username or password');
            }

            req.session.user = {
                id: user.id,
                username: user.username,
                isAdmin: user.isAdmin
            };

            res.status(200).send('Login successful');
        } catch (err) {
            console.error(err);
            res.status(500).send('Server error');
        }
    });
});

app.get('/isLoggedIn', (req, res) => {
    if (req.session.user) {
        res.status(200).json({ loggedIn: true, user: req.session.user });
    } else {
        res.status(200).json({ loggedIn: false });
    }
});

app.get('/isAdmin', (req, res) => {
    if (req.session.user && req.session.user.isAdmin) {
        res.status(200).json({ isAdmin: true });
    } else {
        res.status(200).json({ isAdmin: false });
    }
});

app.get('/logout', (req, res) => {
    req.session.destroy((err) => {
        if (err) {
            console.error(err);
            res.status(500).send('Error logging out');
        } else {
            res.redirect('/login.html');
        }
    });
});

app.post('/addGame', (req, res) => {
    const { gameName, gamePrice, gameImage } = req.body;

    if (!gameName || !gamePrice || !gameImage) {
        return res.status(400).send('All fields are required');
    }

    const sql = 'INSERT INTO games (gameName, gamePrice, gameSplashImage) VALUES (?, ?, ?)';
    db.query(sql, [gameName, gamePrice, gameImage], (err, result) => {
        if (err) {
            console.error(err);
            return res.status(500).send('Server error');
        }

        res.status(200).send({ success: true, message: 'Game added successfully' });
    });
});

app.patch('/editGame/:id', (req, res) => {
    const gameId = req.params.id;
    const { gameName, gamePrice, gameImage } = req.body;

    const updates = [];
    const values = [];

    if (gameName && typeof gameName === 'string' && gameName.trim().length > 0) {
        updates.push('gameName = ?');
        values.push(gameName.trim());
    }

    if (gamePrice && !isNaN(gamePrice) && gamePrice > 0) {
        updates.push('gamePrice = ?');
        values.push(parseFloat(gamePrice));
    }

    if (gameImage && typeof gameImage === 'string' && gameImage.trim().length > 0) {
        updates.push('gameSplashImage = ?');
        values.push(gameImage.trim());
    }

    if (updates.length === 0) {
        return res.status(400).send('At least one valid field is required to update');
    }

    const sql = `UPDATE games SET ${updates.join(', ')} WHERE id = ?`;
    values.push(gameId);

    db.query(sql, values, (err, result) => {
        if (err) {
            console.error('Error updating game:', err);
            return res.status(500).send('Server error');
        }

        if (result.affectedRows === 0) {
            return res.status(404).send('Game not found');
        }

        res.status(200).send({ success: true, message: 'Game updated successfully' });
    });
});

app.delete('/deleteGame/:id', (req, res) => {
    const gameId = req.params.id;

    const sql = 'DELETE FROM games WHERE id = ?';
    db.query(sql, [gameId], (err, result) => {
        if (err) {
            console.error(err);
            return res.status(500).send('Server error');
        }

        if (result.affectedRows === 0) {
            return res.status(404).send('Game not found');
        }

        res.status(200).send({ success: true, message: 'Game deleted successfully' });
    });
});

app.patch('/toggleAdmin/:id', (req, res) => {
    const userId = req.params.id;
    const { isAdmin } = req.body;

    const sql = 'UPDATE Users SET isAdmin = ? WHERE id = ?';
    const values = [isAdmin, userId];
    db.query(sql, values, (err, result) => {
        if (err) {
            console.error('Error updating user admin status:', err);
            res.status(500).send({ success: false, message: 'Failed to update user admin status.' });
        } else {
            console.log('User admin status updated successfully.');
            res.status(200).send({ success: true, message: 'Admin status altered successfully' });
        }
    });
});

app.post('/addToCart', (req, res) => {
    const { game } = req.body;
    const userId = req.session.user.id;

    const checkSql = 'SELECT * FROM cart_items WHERE userID = ? AND gameID = ?';
    db.query(checkSql, [userId, game.id], (err, result) => {
        if (err) {
            console.error('Error checking if game is already in cart:', err);
            return res.status(500).send('Error adding game to cart');
        }

        if (result.length > 0) {
            const updateSql = 'UPDATE cart_items SET quantity = quantity + 1 WHERE userID = ? AND gameID = ?';
            db.query(updateSql, [userId, game.id], (err, result) => {
                if (err) {
                    console.error('Error updating quantity in cart:', err);
                    return res.status(500).send('Error updating quantity in cart');
                }
                res.status(200).send('Quantity updated in cart');
            });
        } else {
            const insertSql = 'INSERT INTO cart_items (userID, gameID, quantity, price) VALUES (?, ?, ?, ?)';
            const values = [userId, game.id, 1, game.gamePrice];
            db.query(insertSql, values, (err, result) => {
                if (err) {
                    console.error('Error adding game to cart:', err);
                    return res.status(500).send('Error adding game to cart');
                }
                res.status(200).send('Game added to cart');
            });
        }
    });
});

app.get('/getCartCount', (req, res) => {
    const userId = req.session.user.id;

    const sql = 'SELECT COUNT(*) AS cartCount FROM cart_items WHERE userID = ?';
    db.query(sql, [userId], (err, result) => {
        if (err) {
            console.error('Error getting cart count:', err);
            return res.status(500).send('Error getting cart count');
        }
        const cartCount = result[0].cartCount;
        res.status(200).json({ cartCount });
    });
});

app.get('/fetchCartItems', (req, res) => {
    const userId = req.session.user.id;

    const sql = 'SELECT * FROM cart_items WHERE userID = ?';
    db.query(sql, [userId], (err, result) => {
        if (err) {
            console.error('Error fetching cart items:', err);
            return res.status(500).send('Error fetching cart items');
        }
        
        res.status(200).json(result);
    });
});

app.get('/fetchGameById/:id', (req, res) => {
    const gameId = req.params.id;

    if (!gameId) {
        return res.status(400).send('Game ID is required');
    }

    const sql = 'SELECT * FROM games WHERE id = ?';
    db.query(sql, [gameId], (err, result) => {
        if (err) {
            console.error('Error fetching game by ID:', err);
            return res.status(500).send('Error fetching game by ID');
        }

        if (result.length === 0) {
            return res.status(404).send('Game not found');
        }

        res.status(200).json(result[0]); 
    });
});

app.post('/updateCartQuantity/:id/:change', (req, res) => {
    const cart_itemID = req.params.id;
    const change = parseInt(req.params.change);
    
    const userId = req.session.user.id;

    const fetchSql = 'SELECT quantity FROM cart_items WHERE cart_itemID = ? AND userID = ?';
    db.query(fetchSql, [cart_itemID, userId], (err, result) => {
        if (err) {
            console.error('Error fetching current quantity:', err);
            return res.status(500).send('Error fetching current quantity');
        }

        if (result.length === 0) {
            return res.status(404).send('Item not found in cart');
        }

        let newQuantity = parseInt(result[0].quantity) + change;

        if (newQuantity <= 0) {
            const deleteSql = 'DELETE FROM cart_items WHERE cart_itemID = ? AND userID = ?';
            db.query(deleteSql, [cart_itemID, userId], (err, result) => {
                if (err) {
                    console.error('Error removing item from cart:', err);
                    return res.status(500).send('Error removing item from cart');
                }
                res.status(200).send('Item removed from cart');
            });
        } else {
            const updateSql = 'UPDATE cart_items SET quantity = ? WHERE cart_itemID = ? AND userID = ?';
            db.query(updateSql, [newQuantity, cart_itemID, userId], (err, result) => {
                if (err) {
                    console.error('Error updating quantity:', err);
                    return res.status(500).send('Error updating quantity');
                }
                res.status(200).send('Quantity updated successfully');
            });
        }
    });
});

app.get('/fetchCartTotal', (req, res) => {
    const userId = req.session.user.id;

    const sql = 'SELECT SUM(price * quantity) AS total FROM cart_items WHERE userID = ?';
    db.query(sql, [userId], (err, result) => {
        if (err) {
            console.error('Error fetching cart total:', err);
            return res.status(500).send('Error fetching cart total');
        }

        const total = result[0].total || 0;
        res.status(200).json({ total });
    });
});

app.post('/buyItems', (req, res) => {
    const { customerEmail, customerNumber, customerAddress } = req.body;

    const userId = req.session.user.id;
    db.query('SELECT * FROM cart_items WHERE userID = ?', [userId], (err, cartItems) => {
        if (err) {
            console.error('Error retrieving cart items:', err);
            return res.status(500).send('Error processing order');
        }

        let totalPrice = 0;
        const items = [];
        for (const item of cartItems) {
            totalPrice += item.price * item.quantity;
            items.push({ id: item.gameID, quantity: item.quantity });
        }

        const order = { customerEmail, customerNumber, customerAddress, items: JSON.stringify(items), totalPrice };
        db.query('INSERT INTO orders SET ?', order, (err, result) => {
            if (err) {
                console.error('Error adding order to database:', err);
                return res.status(500).send('Error processing order');
            }

            db.query('DELETE FROM cart_items WHERE userID = ?', [userId], (err, result) => {
                if (err) {
                    console.error('Error removing items from cart:', err);
                    return res.status(500).send('Error processing order');
                }

                res.status(200).json({ success: true });
            });
        });
    });
});

app.listen(3001, () => {
    console.log("Server running.");
});
