from keras.models import Sequential, load_model, Model
from keras.layers import Dense, Input, Reshape
import numpy as np
import pickle
import argparse
from file_manager import FileManager
from sklearn.preprocessing import StandardScaler
import os
import tensorflow as tf
from keras.optimizers import Adam
from keras.losses import MeanAbsolutePercentageError
from tqdm import tqdm


class DiabetesModel:
    def __init__(self, user_id, batch_size=512):
        self.ordered_x_headers = ["cannula_changing_status", "desease_status", "fast_chs", "insulin", "long_chs", "middle_chs", "minutes", "physical_activity_intensity", "sleeping_status", "stress_level", "sugar_level"]
        self.target_column = "sugar_level"
        self.is_scaler_trained = False
        self.batch_size = batch_size
        self.fm = FileManager(user_id)
        if os.path.exists(self.fm.scaler_file_path):
            with open(self.fm.scaler_file_path, 'rb') as f:
                self.scaler = pickle.load(f)
                self.is_scaler_trained = True
        else:
            self.scaler = StandardScaler()
        self.num_features = len(self.ordered_x_headers)
        self.is_trained = False
        self.model = self.build_model()
        if os.path.exists(self.fm.model_file_path):
            self.model = self.load(self.fm.model_file_path)
            self.is_trained = True

            

    def build_model(self):
        """Создание LSTM модели."""
        inp = Input(shape=(self.batch_size, self.num_features))

        reshaped = Reshape((self.batch_size * self.num_features,))(inp)

        d_1 = Dense(256, activation='sigmoid')(reshaped)

        d_2 = Dense(128, activation='sigmoid')(d_1)

        d_3 = Dense(64, activation='sigmoid')(d_2)

        otp = Dense(1, activation='linear')(d_3)

        model = Model(inp, otp)
        opt = Adam()

        mape = MeanAbsolutePercentageError()

        model.compile(optimizer=opt, loss=mape, metrics=['mae'])
        return model

    def train(self, datasets, epochs=75):
        """Обучение модели."""
        full_xs, full_ys = None, None
        for dataset in datasets:
            xs, ys = self.get_prepared_dataset(dataset)
            if full_xs is None:
                full_xs = xs
                full_ys = ys
            else:
                full_xs = np.concatenate((full_xs, xs), axis=0)
                full_ys = np.concatenate((full_ys, ys), axis=0)
        self.model.fit(full_xs, full_ys, epochs=epochs, shuffle=True, batch_size=1, validation_split=0.2)
        self.is_trained = True
        self.model.save(self.fm.model_file_path)

    def get_prepared_dataset(self, dataset):
        new_xs = []
        new_ys = []
        xs, ys = self.split_x_y(dataset)
        for i in tqdm(range(self.batch_size, len(dataset))):
            new_xs.append(xs[i - self.batch_size:i])
            new_ys.append(ys[i])
        new_xs = np.array(new_xs)
        new_ys = np.array(new_ys)
        return new_xs, new_ys

    def evaluate(self, X, y):
        """Оценка модели."""
        return self.model.evaluate(X, y)

    def predict(self, X):
        """Предсказание с помощью модели."""
        return self.model.predict(X, verbose=0)
        
    def test_predict_multiple(self, X):
        """Предсказание с помощью модели."""
        dataset_values = []
        prediction_values = []
        inp = X[0:self.batch_size - 1]

        predicted_sugar = X.at[self.batch_size - 1,'sugar_level']
        for i in range(self.batch_size, len(X)):
            new_row = X.iloc[i - 1].copy()
            new_row.iloc[-1] = predicted_sugar
            if i != self.batch_size:
                inp = inp.iloc[1:]

            inp.loc[i] = list(new_row.copy())

            xs, ys = self.split_x_y(inp)

            xs = np.reshape(xs, (1, xs.shape[0], xs.shape[1]))

            predicted_sugar = self.predict(xs)[-1]

            dataset_values.append(X.at[i,'sugar_level'])
            prediction_values.append(predicted_sugar)

        return prediction_values

    def save(self, file_path):
        """Сохранение модели."""
        self.model.save(file_path)

    def load(self, file_path):
        """Загрузка модели."""
        return load_model(file_path)

    def split_x_y(self, dataframe):
        x = dataframe[self.ordered_x_headers]
        if not self.is_scaler_trained:
            self.scaler.fit(x)
            with open(self.fm.scaler_file_path, 'wb') as f:
                pickle.dump(self.scaler, f)
                self.is_scaler_trained = True
        scaled_x = self.scaler.transform(x)
        y = dataframe[self.target_column]
        return scaled_x, y.to_numpy()