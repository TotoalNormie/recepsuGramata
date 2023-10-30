INSERT
    IGNORE INTO `recipes` (
        `ID`,
        `title`,
        `description`,
        `image_url`,
        `ingredient_json`,
        `views`,
        `owner`
    )
VALUES
    (
        NULL,
        'Mac&cheese',
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eu odio lobortis, placerat tortor sit amet, congue massa. Nunc malesuada dignissim metus vitae cursus. Nunc in diam elit. Nam eget metus id elit imperdiet vulputate nec sed ante. Vivamus in velit id ipsum vehicula tincidunt. Aenean vel rhoncus ligula. Aliquam id est et sem blandit laoreet nec vehicula massa. In nec elit sed tortor congue rhoncus. Suspendisse et risus et dolor maximus rhoncus et eget risus. Proin at sapien lectus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla consectetur varius purus id dictum. Pellentesque convallis eros eu viverra sodales. In sollicitudin metus mi, vitae porttitor turpis commodo in. Sed elementum massa at ipsum rhoncus, eget convallis nulla interdum. Vestibulum interdum nulla at augue fermentum lacinia.',
        'https://sugarspunrun.com/wp-content/uploads/2023/03/Easy-mac-and-cheese-recipe-1-of-1.jpg',
        '[{"name":"sals","value":"3","mesurment":"karotes"},{"name":"cukurs","value":"25","mesurment":"g"},{"name":"milti","value":"0.5","mesurment":"kg"}]',
        '0',
        ''
    ),
    (
        NULL,
        'Spagetti bolgonese',
        'Nulla placerat velit nec odio rhoncus, tincidunt elementum libero hendrerit. Cras vitae sem sed erat consectetur ultrices mattis sed est. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc eleifend, diam non dapibus suscipit, ex mi sodales libero, id egestas magna est eget magna. Integer euismod condimentum facilisis. Fusce ut est euismod, ornare lacus in, auctor augue. Ut sagittis pellentesque ornare. Integer volutpat nisi ac tincidunt fringilla. Maecenas vel dignissim nulla, vitae placerat ante. Fusce est augue, luctus eget efficitur et, aliquet non ipsum. Sed accumsan, est vitae placerat ultrices, mi lacus bibendum augue, ac laoreet neque odio et erat. Donec lobortis aliquet dictum. Praesent id neque orci.',
        'https://thecozycook.com/wp-content/uploads/2019/08/Bolognese-Sauce-500x500.jpg',
        '[{"name":"sals","value":"3","mesurment":"karotes"},{"name":"cukurs","value":"25","mesurment":"g"},{"name":"milti","value":"0.5","mesurment":"kg"}]',
        '0',
        ''
    ),
    (
        NULL,
        'Meatballs',
        'Mauris a porta felis. Aenean quis lacus ipsum. Suspendisse scelerisque ex eget lorem consectetur tempor. Nam sodales, neque sit amet tempor tempus, tortor orci mattis mi, ac ornare felis ligula ut metus. Maecenas libero diam, dignissim vel metus et, faucibus consequat arcu. Maecenas sed malesuada leo, sed elementum sapien. Sed ac ipsum sed mauris accumsan vestibulum. Duis varius augue velit, sed malesuada est suscipit molestie. Maecenas iaculis leo non lorem sollicitudin, non maximus felis consequat. Cras iaculis tellus libero, sed placerat erat aliquet nec. Vestibulum ac venenatis felis. Nam convallis placerat pharetra. Quisque sollicitudin feugiat risus non convallis. Nunc aliquet posuere mollis. Nullam laoreet dui semper venenatis condimentum.',
        'https://drivemehungry.com/wp-content/uploads/2021/11/italian-meatballs-f.jpg',
        '[{"name":"sals","value":"3","mesurment":"karotes"},{"name":"cukurs","value":"25","mesurment":"g"},{"name":"milti","value":"0.5","mesurment":"kg"}]',
        '0',
        ''
    ),
    (
        NULL,
        'Dumplings',
        'Curabitur sodales tincidunt justo in lacinia. Sed dignissim metus tellus. Ut quis rhoncus turpis. Mauris blandit metus eu magna posuere rutrum. Mauris commodo sagittis velit at euismod. Phasellus magna erat, tincidunt sit amet rhoncus eget, fermentum ac nunc. Integer eu neque ac massa tempor sodales id eu nisi. Nunc ac leo libero. Praesent pellentesque, enim vel accumsan mollis, mauris neque varius neque, volutpat efficitur felis ligula vulputate sem.',
        'https://www.gourmetfoodworld.com/images/Product/medium/chicken-dumplings-recipe-1S-12786.jpg',
        '[{"name":"sals","value":"3","mesurment":"karotes"},{"name":"cukurs","value":"25","mesurment":"g"},{"name":"milti","value":"0.5","mesurment":"kg"}]',
        '0',
        ''
    ),
    (
        NULL,
        'Fried chicken',
        'Quisque elementum eleifend arcu, vitae luctus dolor accumsan nec. Aliquam sed ante nec lorem venenatis ullamcorper at a mauris. Fusce eget bibendum erat. Nunc vitae auctor magna. Proin fringilla turpis id molestie cursus. Aenean lobortis libero massa, eget rhoncus ipsum semper a. Sed felis elit, feugiat vitae tellus a, luctus volutpat lorem. Donec nec ante ut sem varius feugiat. Phasellus at dignissim erat, in gravida dui. Vestibulum id tincidunt nisi.',
        'https://www.foodandwine.com/thmb/JMrJBrYh3fxDRgkV24_8dZH_zpQ=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/FAW-recipes-crispy-buttermilk-fried-chicken-hero-04-3a32f9a4a1984ecab79fb28e93d4bc00.jpg',
        '[{"name":"sals","value":"3","mesurment":"karotes"},{"name":"cukurs","value":"25","mesurment":"g"},{"name":"milti","value":"0.5","mesurment":"kg"}]',
        '0',
        ''
    ),
    (
        NULL,
        'Pilsetas kebabs',
        'Maecenas non scelerisque sapien. Ut ex ligula, sollicitudin vitae odio faucibus, commodo porttitor purus. Sed imperdiet euismod orci, porta aliquam nibh. Donec porta at mauris dapibus venenatis. Cras tincidunt dolor sed vestibulum imperdiet. Vestibulum vitae ipsum iaculis, aliquet leo at, blandit tortor. Sed facilisis tincidunt justo nec tincidunt. In nec accumsan quam, eu tincidunt dolor. Nunc id nisi sem. Nunc a ipsum est. Mauris nec sodales ligula.',
        'https://10619-2.s.cdn12.com/rests/original/108_514629166.jpg',
        '[{"name":"sals","value":"3","mesurment":"karotes"},{"name":"cukurs","value":"25","mesurment":"g"},{"name":"milti","value":"0.5","mesurment":"kg"}]',
        '0',
        ''
    );