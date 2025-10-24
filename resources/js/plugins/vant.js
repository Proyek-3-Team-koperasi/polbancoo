import {
    Button,
    Card,
    Cell,
    CellGroup,
    ConfigProvider,
    Icon,
    NavBar,
    Popup,
    Tabbar,
    TabbarItem,
    Tag,
    Grid,
    GridItem,
    Image,
    Field,
    Radio,
    RadioGroup,
} from "vant";

export function registerVant(app) {
    app.use(ConfigProvider);
    app.use(Button);
    app.use(Card);
    app.use(Tag);
    app.use(Icon);
    app.use(CellGroup);
    app.use(Cell);
    app.use(Popup);
    app.use(Tabbar);
    app.use(TabbarItem);
    app.use(NavBar);
    app.use(Grid);
    app.use(GridItem);
    app.use(Image);
    app.use(Field);
    app.use(Radio);
    app.use(RadioGroup);
}
